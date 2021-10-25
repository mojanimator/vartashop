<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Group;
use App\Models\Image;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function images(Request $request)
    {
        if ($request->p_id)
            return Image::where('type', 'p')->where('for_id', $request->p_id)->pluck('id');
    }

    public function groups(Request $request)
    {
        $level = $request->level;
        $showTree = $request->show_tree;
        $ids = $request->ids;

        if (!$ids)
            $ids = Group::where('level', 1)->distinct('parent')->pluck('parent');

        $query = Group::query();

        if ($showTree) {
            return $query->select('id', 'name')->whereIn('id', $ids)->orderByDesc('id')->get()->map(function ($data) {
                $data['childs'] = Group::where('parent', $data['id'])->select('id', 'name')->get()->map(function ($data) {
                    $data['selected'] = false;
                    return $data;
                });

                return $data;

            });
        }
        if ($level)
            $query->where('level' . $level);


        return $query->get();
    }

    public function create(Request $request)
    {
        $this->authorize('ownShop', [User::class, $request->shop_id, false]);

        $request->validate([

            'shop_id' => 'required',
            'group_id' => 'required|' . Rule::in(Group::pluck('id')),
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:1024',
            'price' => 'required|numeric' . ((int)$request->price > 0 ? "|digitsbetween:4,10|gt:discount_price" : "|gte:discount_price"),
            'discount_price' => 'required|numeric' . ((int)$request->discount_price > 0 ? "|digitsbetween:4,10|lt:price" : ''),
            'count' => 'required|numeric|min:0|digitsbetween:1,10',
            'tags' => 'required|string|max:100',
            'images' => 'required|array|min:1|max:' . \Helper::$product_image_limit,
            'images.*' => 'required|base64_image|base64_size:2048',
        ], [

            'shop_id.required' => 'انتخاب فروشگاه ضروری است',

            'group_id.required' => 'دسته بندی محصول ضروری است',
            'group_id.in' => 'دسته بندی نامعتبر است',
            'name.string' => 'نام باید متنی باشد',
            'name.required' => 'نام محصول ضروری است',
            'name.max' => 'حداکثر طول نام 100 باشد. طول فعلی: ' . mb_strlen($request->name),
            'description.required' => 'توضیحات محصول ضروری است',
            'description.string' => 'توضیحات باید متنی باشد',
            'description.max' => 'حداکثر طول توضیحات 1024 کلمه باشد. طول فعلی: ' . mb_strlen($request->description),
            'price.numeric' => 'قیمت  عددی باشد',
            'price.digitsbetween' => 'قیمت صفر باشد یا حداقل 4 رقم باشد',
            'price.gt' => 'قیمت اصلی از قیمت حراج بیشتر باشد',
            'price.gte' => 'قیمت اصلی از قیمت با تخفیف بیشتر باشد',
            'discount_price.numeric' => 'قیمت  عددی باشد',
            'discount_price.digitsbetween' => 'قیمت صفر باشد یا حداقل 4 رقم باشد',
            'discount_price.lt' => 'قیمت حراج از قیمت اصلی کمتر باشد',
            'count.numeric' => 'تعداد محصول عددی باشد',
            'count.min' => 'حداقل تعداد صفر باشد',
            'count.digitsbetween' => 'تعداد ارقام نامعتبر است',
            'tags.required' => 'هشتگ های محصول ضروری است',
            'tags.string' => 'تگ های محصول متنی باشد',
            'tags.max' => 'حداکثر طول تگ ها 100 باشد. طول فعلی: ' . mb_strlen($request->tags),

            'images.required' => 'حداقل یک تصویر ضروری است',
            'images.array' => 'حداقل یک تصویر ضروری است',
            'images.min' => 'حداقل یک تصویر ضروری است',
            'images.max' => 'حداکثر تصاویر ' . \Helper::$product_image_limit . ' عدد است ',

            'images.*.image' => 'فرمت تصویر از نوع  jpg باشد',
            'images.*.mimes' => 'فرمت تصویر از نوع  jpg باشد',
            'images.*.max' => 'حداکثر حجم فایل 2 مگابایت باشد',


        ]);
        $user = auth()->user();

        if ($user->score < Helper::$create_product_score)
            throw ValidationException::withMessages(['name' => "برای ساخت محصول " . Helper::$create_product_score . " سکه نیاز دارید "]);


        $name = $request->name;
        $group_id = $request->group_id;
        $shop_id = $request->shop_id;
        $description = $request->description;
        $tags = $request->tags;

        $discount_price = $request->discount_price;
        $price = $request->price;
        $count = $request->count;

        $product = Product::create([
            'active' => true,
            'name' => $name,
            'group_id' => $group_id,
            'shop_id' => $shop_id,
            'description' => $description,
            'tags' => $tags,
            'price' => $price,
            'discount_price' => $discount_price,

            'count' => $count,
        ]);

        foreach ($request->images as $img) {
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $image = Image::create(['type' => 'p', 'for_id' => $product->id]);

            file_put_contents(storage_path("app/public/products/$image->id.jpg"), $image_base64);

        }


//            $img->storeAs("public/shops", "$shop->id.jpg");

        $user->score = $user->score - Helper::$create_product_score;
        $user->save();

        $shop = Shop::findOrNew($shop_id);
        logAdmins(" ✅🛒 " . " یک محصول اضافه شد " . PHP_EOL . "فروشگاه: $shop->name" . PHP_EOL . "محصول: $product->name" . PHP_EOL . Channel::where('chat_id', "$shop->channel_address")->firstOrNew()->chat_username);

        $this->sendProductBanner($product);

        return redirect()->to('panel/my-products')->with('success-alert', 'محصول شما با موفقیت ساخته شد!');

    }

    public function edit(Request $request)
    {
        $this->authorize('ownProduct', [User::class, $request->id, false]);
        $id = $request->id;
        $product = Product::withoutGlobalScopes()->where('id', $id)->first();
        if (!$product)
            throw ValidationException::withMessages(['id' => "محصول یافت نشد"]);


        $request->validate([
            'cmnd' => 'required_if:img,!=,null',
            'id' => 'required|numeric',
            'group_id' => 'sometimes|' . Rule::in(Group::pluck('id')),
            'name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:1024',
            'price' => 'sometimes|numeric' . ((int)$request->price > 0 ? "|digitsbetween:4,10|gt:$product->discount_price" : "|gte:$product->discount_price"),
            'discount_price' => 'sometimes|numeric' . ((int)$request->discount_price > 0 ? "|digitsbetween:4,10|lt:$product->price" : ''),
            'count' => 'sometimes|numeric|min:0|digitsbetween:1,10',
            'tags' => 'sometimes|string|max:100',
            'active' => 'sometimes|boolean',
            'img' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'img_id' => 'sometimes|' . Rule::in(Image::where('type', 'p')->where('for_id', $product->id)->pluck('id'))
        ], [
            'id.required' => 'شناسه ضروری است',
            'id.numeric' => 'شناسه عددی است',
            'cmnd.required' => 'نوع فرمان ضروری است',
            'group_id.in' => 'دسته بندی نامعتبر است',
            'name.string' => 'نام باید متنی باشد',
            'name.max' => 'حداکثر طول نام 100 باشد. طول فعلی: ' . mb_strlen($request->name),
            'description.string' => 'توضیحات باید متنی باشد',
            'description.max' => 'حداکثر طول توضیحات 1024 کلمه باشد. طول فعلی: ' . mb_strlen($request->description),
            'price.numeric' => 'قیمت  عددی باشد',
            'price.digitsbetween' => 'قیمت صفر باشد یا حداقل 4 رقم باشد',
            'price.gt' => 'قیمت اصلی از قیمت حراج بیشتر باشد',
            'price.gte' => 'قیمت اصلی از قیمت با تخفیف بیشتر باشد',
            'discount_price.numeric' => 'قیمت  عددی باشد',
            'discount_price.digitsbetween' => 'قیمت صفر باشد یا حداقل 4 رقم باشد',
            'discount_price.lt' => 'قیمت حراج از قیمت اصلی کمتر باشد',
            'count.numeric' => 'تعداد محصول عددی باشد',
            'count.min' => 'حداقل تعداد صفر باشد',
            'count.digitsbetween' => 'تعداد ارقام نامعتبر است',
            'tags.string' => 'تگ های محصول متنی باشد',
            'tags.max' => 'حداکثر طول تگ ها 100 باشد. طول فعلی: ' . mb_strlen($request->tags),

            'img.image' => 'فایل از نوع تصویر باشد',
            'img.mimes' => 'فرمت تصویر از نوع  jpg باشد',
            'img.max' => 'حداکثر حجم فایل 2 مگابایت باشد',
            'img_id.in' => 'شناسه عکس نامعتبر است',

        ]);


        $imgId = $request->img_id;
        $cmnd = $request->cmnd;
        $name = $request->name;
        $group_id = $request->group_id;
        $description = $request->description;
        $price = $request->price;
        $discount_price = $request->discount_price;
        $count = $request->count;
        $tags = $request->tags;
        $active = $request->active;
        $img = $request->file('img');


        if ($img) {
//            $name = $img->getClientOriginalName();
            if (Image::where('type', 'p')->where('for_id', $product->id)->count() >= \Helper::$product_image_limit)
                throw ValidationException::withMessages(['id' => "حداکثر عکس مجاز برای محصول " . \Helper::$product_image_limit . " عدد است"]);
            $image = Image::create(['type' => 'p', 'for_id' => $product->id]);
            $img->storeAs("public/products", "$image->id.jpg");
            return redirect()->back()->with('success-alert', 'تصویر با موفقیت ویرایش شد!');
        } elseif ($name) {
            $product->name = $name;
            $product->save();
        } elseif ($group_id) {
            $product->group_id = $group_id;
            $product->save();
        } elseif ($description) {
            $product->description = $description;
            $product->save();
        } elseif (isset($price)) {
            $product->price = $price;
            $product->save();
        } elseif (isset($discount_price)) {
            $product->discount_price = $discount_price;
            $product->save();
        } elseif (isset($count)) {
            $product->count = $count;
            $product->save();
        } elseif ($tags) {
            $product->tags = $tags;
            $product->save();
        } elseif (isset($active)) {
            $product->active = $active;
            $product->save();
        } elseif ($cmnd == 'del-prod') {

            foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $img) {
                if (Storage::exists("public/products/$img->id.jpg")) {
                    Storage::delete("public/products/$img->id.jpg");
                }
                $img->delete();
            }
            $product->delete();

        } elseif ($cmnd == 'del-img') {

            $img = Image::findOrNew($imgId);
            $img->delete();
            Storage::delete("public/products/$img->id.jpg");


        }
    }

    public function search(Request $request)
    {

//        $query = Report::query();
//        $request->validate([
//
//            'paginate' => 'sometimes|numeric',
//            'page' => 'sometimes|numeric',
//        ], []);


        $scope = $request->scope;
        $shop_ids = $request->shop_ids;
        $search = $request->search;
        $idNot = $request->id_not;
        $name = $request->name;
        $tags = $request->tags;
        $paginate = $request->paginate;
        $page = $request->page;
        $group_id = $request->group_id;
        $is_vip = $request->is_vip;
        $orderByRaw = $request->order_by_raw;
        $orderBy = $request->order_by;
        $dir = $request->dir;
        $shopId = $request->shop_id;
        $groupIds = $request->group_ids;


        if (!$paginate) {
            $paginate = 12;
        }
        if (!$page) {
            $page = 1;
        }
        if (!$dir) {
            $dir = 'DESC';
        }


        $query = Product::query();

        if ($shop_ids)
            $query = $query->whereIn('shop_id', $shop_ids)->withoutGlobalScopes();

//        if ($idNot)
//            $query = $query->where('id', '!=', $idNot);

        if ($search) {
            $query = $query->where(function ($query) use ($search) {

                foreach (array_filter(explode(" ", $search), function ($el) {
                    return $el != '' && $el != ' ' && $el != null;
                }) as $word) {
                    $query->orWhere('name', 'LIKE', '%' . $word . '%')->orWhere('tags', 'LIKE', '%' . $word . '%');
                }

            });

        }

        if ($name)
            $query = $query->where(function ($query) use ($name) {

                foreach ($name as $word) {
                    $query->orWhere('name', 'LIKE', '%' . $word . '%');
                }

            });
        if ($tags)
            $query = $query->where(function ($query) use ($tags) {

                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'LIKE', '%' . $tag . '%');
                }

            });

        if ($groupIds) {
            $g = Group::whereIn('parent', $groupIds)->orWhereIn('id', $groupIds)->pluck('id');

            $query = $query->whereIn('group_id', $g);

        }

        if ($shopId)
            $query = $query->where('shop_id', $shopId);


        if ($orderBy)
            $query = $query->orderBy($orderBy, $dir);
        elseif ($orderByRaw)
            $query = $query->orderByRaw($orderByRaw);
        else
            $query = $query->inRandomOrder();

        $query = $query->with('shop');

        if ($shop_ids)
            $query = $query->with('group');

//        $data = $query->offset($page - 1)->limit($paginate)->get();
        if (is_numeric($page))
            $data = $query->paginate($paginate, ['*'], 'page', $page);

//        foreach ($data as $idx => $item) {
//            $img = \App\Models\Image::on(env('DB_CONNECTION'))->where('type', 'p')->where('for_id', $item->id)->inRandomOrder()->first();
//            if ($img)
//                $item['img'] = $img->id . '.jpg';
//        }

        return $data;
    }

    public function sendProductBanner($product)
    {

        $shop = Shop::where('id', $product->shop_id)->first();
        $channel = Channel::where('chat_id', "$shop->channel_address")->first();
        $tag = ($channel->tag) ?? "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $channel->chat_username;

        $caption = ($product->discount_price > 0 ? "🔥 #حراج" : "") . PHP_EOL;
        $caption .= ' 🆔 ' . "کد محصول: #" . $product->id . PHP_EOL;
        $caption .= ' 🔻 ' . "نام: " . $product->name . PHP_EOL;
//                    $caption .= ' ▪️ ' . "تعداد موجود: " . $product->count . PHP_EOL;
        $caption .= ' 🔸 ' . "قیمت: " . ($product->price == 0 ? 'پیام دهید' : number_format($product->price) . ' ت ') . PHP_EOL;
        if ($product->discount_price > 0)
            $caption .= ' 🔹 ' . "قیمت حراج: " . number_format($product->discount_price) . ' ت ' . PHP_EOL;
        $caption .= ' 🔻 ' . "توضیحات: " . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL . $product->description . PHP_EOL . "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
        $caption .= $product->tags . PHP_EOL;
        $caption .= $tag . PHP_EOL;
        $caption = MarkDown($caption);

        $images = [];

        foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
            $images[] = ['type' => 'photo', 'media' => str_replace('https://', '', asset("storage/products/$item->id.jpg")), 'caption' => $caption, 'parse_mode' => 'Markdown',];
        }
        if (count($images) == 0) {
            if (Storage::exists("public/shops/$shop->id.jpg")) {
                sendTelegramPhoto(Helper::$channel, asset("storage/shops/$shop->id.jpg"), $caption, null, null);
                $res = sendTelegramPhoto($channel->chat_username, asset("storage/shops/$shop->id.jpg"), $caption, null, null);

            } else {
                sendTelegramMessage(Helper::$channel, $caption, null, null);
                $res = sendTelegramMessage($channel->chat_username, $caption, null, null);

            }
        } elseif (count($images) == 1) {

            $res = sendTelegramPhoto(Helper::$channel, $images[0]['media'], $caption, null, null);
            $res = sendTelegramPhoto($channel->chat_id, $images[0]['media'], $caption, null, null);
        } else {
            $images = [];
            foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $item) {
                if ($caption) {
                    $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg"), 'caption' => $caption, 'parse_mode' => 'Markdown',];
                    $caption = null;
                } else {

                    $images[] = ['type' => 'photo', 'media' => asset("storage/products/$item->id.jpg")];
                }

            }
            $res = sendTelegramMediaGroup(Helper::$channel, $images);
            $res = sendTelegramMediaGroup($channel->chat_id, $images);

        }

    }
}
