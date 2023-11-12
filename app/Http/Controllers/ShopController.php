<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserRequest;
use App\Mail\RegisterEditUserMail;
use App\Models\Channel;
use App\Models\County;
use App\Models\Group;
use App\Models\Image;
use App\Models\Province;
use App\Models\Shop;
use App\Models\User;

use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Morilog\Jalali\CalendarUtils;
use PHPUnit\TextUI\Help;

class ShopController extends Controller
{
    public function __construct()
    {
//        parent::__construct();
        $this->middleware('auth')->except(['show']);
    }

    public function show()
    {

        return view('user.show');
    }

    public function showPanel()
    {

        return view('user.panel');
    }

    public function view()
    {

        return view('user.users');
    }

    public function delete(Request $request)
    {
        if (auth()->user()->role != 'go')
            return abort(403, 'مجاز به حذف فروشگاه نیستید!');

        $shop = Shop::find($request->shop_id);
        foreach ($shop->products as $product) {
            foreach (Image::where('type', 'p')->where('for_id', $product->id)->get() as $img) {
                if (Storage::exists("public/products/$img->id.jpg")) {
                    Storage::delete("public/products/$img->id.jpg");
                }
                $img->delete();
            }
            $product->delete();
        }
        Channel::where('chat_id', "$shop->channel_address")->delete();
        if (Storage::exists("public/shops/$shop->id.jpg")) {
            Storage::delete("public/shops/$shop->id.jpg");
        }
        $shop->delete();
        logAdmins(" ✅🛒 " . " یک فروشگاه حذف شد " . PHP_EOL . $shop->name);

        return redirect()->back()->with('success-alert', 'فروشگاه با موفقیت حذف شد');

    }

    protected function create(Request $request)
    {


        $request->channel_username = $request->channel_username ? ('@' . str_replace('@', '', $request->channel_username)) : null;

        $request->validate([

            'img' => 'required|base64_image|base64_size:2048',
            'name' => 'required|string|min:5|max:50',
            'group_id' => 'required|' . Rule::in(Group::pluck('id')),
            'description' => 'required|string|max:500',
            'county_id' => 'required|' . Rule::in(County::pluck('id')),
            'province_id' => 'required|in:' . County::where('id', $request->county_id)->firstOrNew()->province_id,
            'postal_code' => 'nullable|numeric|digitsbetween:0,20',
            'address' => 'required|string|min:0|max:500',
            'channel_username' => "nullable|max:50|unique:channels,chat_username"
        ],
            [
                'name.required' => 'نام  ضروری است',
                'name.string' => 'نام  نمی تواند عدد باشد',
                'name.min' => 'نام  حداقل 5 حرف باشد',
                'name.max' => 'نام  حداکثر 50 حرف باشد',

                'img.required' => 'تصویر ضروری است',
                'img.base64_image' => 'فایل از نوع تصویر باشد',
                'img.base64_size' => 'حداکثر حجم فایل 2 مگابایت باشد',

                'group_id.required' => 'دسته بندی ضروری است',
                'group_id.in' => 'دسته بندی نامعتبر است',

                'description.required' => 'توضیحات ضروری است',
                'description.string' => 'توضیحات متنی باشد',
                'description.max' => 'حداکثر طول توضیحات ۵۰۰ کلمه باشد. طول فعلی: ' . mb_strlen($request->description),

                'county_id.required' => 'شهر ضروری است',
                'county_id.in' => 'شهر نامعتبر است',
                'province_id.required' => 'استان ضروری است',
                'province_id.in' => 'استان نامعتبر است',

                'postal_code.numeric' => 'کد پستی عددی باشد',
                'postal_code.digitsbetween' => 'کد پستی حداکثر 20 عدد باشد',

                'address.required' => 'آدرس ضروری است',
                'address.string' => 'آدرس باید متن باشد',
                'address.max' => 'آدرس  حداکثر 500 حرف باشد',

                'channel_username.max' => 'کانال  حداکثر 50 حرف باشد',
                'channel_username.unique' => 'این کانال متعلق به یک فروشگاه دیگر است'
            ]);

//

        return DB::transaction(function () use ($request) {

            $user = auth()->user();

            $name = $request->name;
            $groupId = $request->group_id;
            $description = $request->description;
            $county_id = $request->county_id;
            $province_id = $request->province_id;
            $postalCode = $request->postal_code;
            $address = $request->address;
            $channelUsername = $request->channel_username;
            $img = $request->img;

            if ($user->score < Helper::$create_shop_score)
                throw ValidationException::withMessages(['name' => "برای ساخت فروشگاه " . Helper::$create_shop_score . " سکه نیاز دارید "]);

            $shop = Shop::create([
                'name' => $name,
                'user_id' => $user->id,
                'group_id' => $groupId,
                'description' => $description,
                'county_id' => $county_id,
                'province_id' => $province_id,
                'postal_code' => $postalCode,
                'address' => $address,
                'phone' => $user->phone,
            ]);

            if ($channelUsername) {

                $res = creator('getChat', ['chat_id' => $channelUsername,]);
                if ($res->ok == false) {
                    $shop->delete();
                    throw ValidationException::withMessages(['channel_username' => "$res->description"]);
                }
                if (!isset($res) || $res->result->type != 'channel') {
                    $shop->delete();
                    throw ValidationException::withMessages(['channel_username' => "ورودی شما از نوع کانال نیست و یا ربات را بلاک کرده اید"]);
                }
                $info = $res->result;

                if (Channel::where('chat_id', "$info->id")->exists()) {
                    $shop->delete();
                    throw ValidationException::withMessages(['channel_username' => "این کانال متعلق به فروشگاه دیگری است"]);
                }

                $chat = Channel::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'chat_id' => "$info->id",
                    'chat_username' => "@" . $info->username,

                ]);

                $shop->channel_address = "$info->id";
                $shop->save();

            }

            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);


            file_put_contents(storage_path("app/public/shops/$shop->id.jpg"), $image_base64);


//            $img->storeAs("public/shops", "$shop->id.jpg");

            $user->score = $user->score - Helper::$create_shop_score;
            $user->save();

            logAdmins(" ✅🛒 " . " یک فروشگاه اضافه شد " . PHP_EOL . $shop->name . PHP_EOL . Channel::where('chat_id', "$shop->channel_address")->firstOrNew()->chat_username);
            sendTelegramMessage(Helper::$channel, " ✅🛒 " . " یک فروشگاه اضافه شد " . PHP_EOL . $shop->name . PHP_EOL . Channel::where('chat_id', "$shop->channel_address")->firstOrNew()->chat_username);


            return redirect()->to('panel/my-shops')->with('success-alert', 'فروشگاه شما با موفقیت ساخته شد!');

        });

    }

    protected function edit(Request $request)
    {
        $this->authorize('ownShop', [User::class, $request->shop_id, false]);

//        dd($request->address);
        $request->channel_username = $request->channel_username ? ('@' . str_replace('@', '', $request->channel_username)) : null;

        $request->validate([

            'img' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'sometimes|string|min:5|max:50',
            'group_id' => 'sometimes|' . Rule::in(Group::pluck('id')),
            'description' => 'sometimes|string|max:500',
            'county_id' => 'sometimes|' . Rule::in(County::pluck('id')),
            'province_id' => 'sometimes|in:' . County::where('id', $request->county_id)->firstOrNew()->province_id,
            'postal_code' => 'sometimes|numeric|digitsbetween:0,20',
            'address' => 'sometimes|string|min:0|max:500',
            'channel_username' => "sometimes|nullable|max:50|unique:channels,chat_username,$request->shop_id,shop_id"
        ],
            ['name.string' => 'نام  نمی تواند عدد باشد',
                'name.min' => 'نام  حداقل 5 حرف باشد',
                'name.max' => 'نام  حداکثر 50 حرف باشد',

                'img.image' => 'فایل از نوع تصویر باشد',
                'img.mimes' => 'فرمت تصویر از نوع  jpg باشد',
                'img.max' => 'حداکثر حجم فایل 2 مگابایت باشد',

                'group_id.in' => 'دسته بندی نامعتبر است',

                'description.string' => 'توضیحات متنی باشد',
                'description.max' => 'حداکثر طول توضیحات ۵۰۰ کلمه باشد. طول فعلی: ' . mb_strlen($request->description),

                'county_id.in' => 'شهر نامعتبر است',
                'province_id.in' => 'استان نامعتبر است',

                'postal_code.numeric' => 'کد پستی عددی باشد',
                'postal_code.digitsbetween' => 'کد پستی حداکثر 20 عدد باشد',

                'address.string' => 'آدرس باید متن باشد',
                'address.max' => 'آدرس  حداکثر 500 حرف باشد',

                'channel_username.max' => 'کانال  حداکثر 50 حرف باشد',
                'channel_username.unique' => 'این کانال متعلق به یک فروشگاه دیگر است'
            ]);

//

        return DB::transaction(function () use ($request) {

            $user = auth()->user();

            $cmnd = $request->cmnd;
            $name = $request->name;
            $groupId = $request->group_id;
            $description = $request->description;
            $county_id = $request->county_id;
            $province_id = $request->province_id;
            $postalCode = $request->postal_code;
            $address = $request->address;
            $channelUsername = $request->channel_username;

            $img = $request->file('img');
            $shopId = $request->id;

            if ($cmnd == 'del-image') {
                Storage::delete("public/shops/$shopId.jpg");
            } elseif ($img) {
                $name = $img->getClientOriginalName();

                $img->storeAs("public/shops", $name);
                return redirect()->back()->with('success-alert', 'تصویر با موفقیت ویرایش شد!');
            } elseif ($name) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['name' => $name]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($groupId) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['group_id' => $groupId]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($description) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['description' => $description]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($county_id) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['county_id' => $county_id, 'province_id' => $province_id,]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($postalCode) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['postal_code' => $postalCode,]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($postalCode) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['postal_code' => $postalCode,]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($address) {
                Shop::withoutGlobalScopes()->where('id', $shopId)->update(['address' => $address,]);
                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            } elseif ($channelUsername || $cmnd == 'channel') {
                $shop = Shop::withoutGlobalScopes()->find($shopId);

                if ($channelUsername == null) {
                    $chat = Channel::where('shop_id', $shopId)->delete();
                    $shop->channel_address = null;
                    $shop->save();
                    return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');

                }

                $res = creator('getChat', ['chat_id' => $channelUsername,]);
                if ($res->ok == false)
                    throw ValidationException::withMessages(['channel_username' => "$res->description"]);
                if (!isset($res) || $res->result->type != 'channel')
                    throw ValidationException::withMessages(['channel_username' => "ورودی شما از نوع کانال نیست و یا ربات را بلاک کرده اید"]);
                $info = $res->result;

                $chat = Channel::orWhere('chat_id', "$info->id")->orWhere('shop_id', $shopId)->first();


                if ($chat) {
                    $chat->chat_username = "@$info->username";
                    $chat->chat_id = "$info->id";
                    $chat->shop_id = $shopId;
                    $chat->user_id = $user->id;

                    $chat->save();
                } else {

                    $chat = Channel::create([
                        'user_id' => $user->id,
                        'shop_id' => $shop->id,
                        'chat_id' => "$info->id",
//
                        'chat_username' => "@" . $info->username,
                    ]);
                }
                $shop->channel_address = "$info->id";
                $shop->save();

                sendTelegramMessage(Helper::$logs[0], ' ✅ ' . "کانال " . "@" . $info->username . " به فروشگاه $shop->name متصل شد");

                return redirect()->back()->with('success-alert', 'با موفقیت ویرایش شد!');
            }


        });

    }

    public function search(Request $request)
    {
        $user_access = auth()->user() ? auth()->user()->role()->first()->permissions : [];
        $is_superuser = count($user_access) > 0 && in_array('all', $user_access) ? true : false;
        if (count($user_access) == 0 || !$is_superuser && !in_array('vu', $user_access)) //user not hooze access
            return [];

        $request->validate([
            'name' => 'nullable|max:100',
            'paginate' => 'nullable|numeric|max:1000000000',
            'page' => 'nullable|numeric|max:1000000000',
//            'for' => 'sometimes|string|in:dropdown',
        ], [
            'name.max' => 'حداکثر طول نام 100 کاراکتر است',
            'paginate.numeric' => 'صفحه بندی نامعتبر است.',
            'paginate.max' => 'صفحه بندی نامعتبر است.',
            'page.numeric' => 'صفحه  نامعتبر است.',
            'page.max' => 'صفحه  نامعتبر است.',
//            'for.string' => 'نوع درخواست حوزه نامعتبر است',
//            'for.in' => 'نوع درخواست حوزه نامعتبر است',
        ]);
        $query = User::where('id', '<>', auth()->user()->id); //not return self user

        if (!$is_superuser)
            $query = $query->where('inline_role', '<>', 1); //only superuser can see superusers

        $name = $request->name;
        $paginate = $request->paginate;
        $page = $request->page;
        $sortBy = $request->sortBy;
        $direction = $request->direction;


        if (!$paginate) {
            $paginate = 24;
        }
        if (!$page) {
            $page = 1;
        }


        if ($name != '')
            $query = $query->where('name', 'like', '%' . $name . '%');


        if ($sortBy && $direction)
            $query = $query->orderBy($sortBy, $direction);

        return $query->with('role')->paginate($paginate, ['*'], 'page', $page);

    }

    protected function resendEmail($email, $token, $from)
    {

//            dispatch(new SendEmailJob($user))->onQueue('default');
        Mail::to($email)->send(new RegisterEditUserMail($token, $from));

        return redirect(route('user.view', [auth()->user()->username]))->with('flash-success-edit', ' پیام تایید ایمیل  برای کاربر ارسال شد');

    }

    protected function create_access(Request $data)
    {
        $roles = array();

        if ($data['access_all'] == true)
            array_push($roles, 'all');
        else {
            if ($data['access_view_schools'] == true)
                array_push($roles, 'vs');
            if ($data['access_create_schools'] == true)
                array_push($roles, 'cs');
            if ($data['access_edit_schools'] == true)
                array_push($roles, 'es');
            if ($data['access_remove_schools'] == true)
                array_push($roles, 'rs');
            if ($data['access_view_users'] == true)
                array_push($roles, 'vu');
            if ($data['access_create_users'] == true)
                array_push($roles, 'cu');
            if ($data['access_edit_users'] == true)
                array_push($roles, 'eu');
            if ($data['access_remove_users'] == true)
                array_push($roles, 'ru');
            if ($data['access_view_hoozes'] == true)
                array_push($roles, 'vh');
            if ($data['access_create_hoozes'] == true)
                array_push($roles, 'ch');
            if ($data['access_edit_hoozes'] == true)
                array_push($roles, 'eh');
            if ($data['access_remove_hoozes'] == true)
                array_push($roles, 'rh');
            if ($data['access_view_reports'] == true)
                array_push($roles, 'vr');


        }
        return $roles;
    }

    public function destroy(Request $request)
    {




        $request->validate([
            'id' => 'required|numeric|min:1|max:4294967295',

        ], [
            'id.required' => 'شناسه کاربر نامعتبر است',
            'id.numeric' => 'شناسه کاربر نامعتبر است',
            'id.min' => 'شناسه کاربر نامعتبر است',
            'id.max' => 'شناسه کاربر نامعتبر است',

        ]);

        DB::transaction(function () use ($request) {
            Schema::disableForeignKeyConstraints();

            User::destroy($request->id);
            Schema::enableForeignKeyConstraints();

            return "200";
        });
        return null;
    }

    public function storeImage(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);

        $name = $request->file('image')->getClientOriginalName();

        $path = $request->file('image')->store('public/users');


        $save = new Photo;

        $save->name = $name;
        $save->path = $path;

        return redirect('panel/settings')->with('status', 'Image Has been uploaded successfully in laravel 8');

    }
}


