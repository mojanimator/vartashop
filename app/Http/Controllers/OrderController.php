<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use App\Models\Cart;
use App\Models\County;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pdf(Request $request)
    {
        if ($request->id != 'new') {
            logAdmins('یک فاکتور ساخته شد' . (Order::where('id', $request->id)->with('shop')->firstOrNew()->shop->name ?? 'نامشخص'));

            $pdf = PDF::loadView('components.admin.order-factor', ['id' => $request->id])/*->setPaper('a4', 'landscape')*/
            ;

            return $pdf->stream('سفارش ' . $request->id . '.pdf');
//        return $pdf->download('سفارش ' . $request->id . '.pdf');}
        } elseif ($request->id == 'new') {

            $order = json_decode(utf8_decode(json_encode($request->all(), JSON_UNESCAPED_UNICODE)), false, 512, JSON_UNESCAPED_UNICODE);


            $txt = $this->getOrderText($order);
            logAdmins('💵 یک فاکتور ساخته شد' . PHP_EOL . $txt);

            $pdf = PDF::loadView('components.admin.order-factor', ['order' => $order])/*->setPaper('a4', 'landscape')*/
            ;

            return $pdf->stream('سفارش ' . utf8_decode($request->name) . '.pdf');
        }
    }

    public function delete(Request $request)
    {


        $this->authorize('delete', [Order::class, $request->order_user_id, $request->shop_id, $request->order_status]);
        $order = Order::where('id', $request->order_id)->first();

        $order->products()->detach();
        $order->delete();
        return redirect()->to('panel/my-orders/search-orders?status=' . $order->status)->with('success', 'سفارش با موفقیت حذف شد');
    }

    public function edit(Request $request)
    {
        $order = Order::find($request->id);
        if (!$order) return redirect()->back()->with('danger-alert', 'سفارش یافت نشد');

        if ($request->cmnd == 'status') {
            $request->validate([
                'status' => 'required|in:1,2,3,4,5',
                'description' => 'nullable|string|max:1024',
                'id' => 'required|numeric|' . Rule::in(Order::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->pluck('id')),
            ], [
                'description.required' => 'توضیحات ضروری است',
                'description.string' => 'توضیحات متنی باشد',
                'description.max' => 'حداکثر طول توضیحات 1024 کلمه باشد. طول فعلی: ' . mb_strlen($request->description),
            ]);

            if ($request->status == 2) { //wait for pay (only shop owner can change)
                if (!in_array($order->shop_id, auth()->user()->shopIds())) {
                    $validator = Validator::make([], []); // Empty data and rules fields
                    $validator->errors()->add('common', 'شما مالک فروشگاه نیستید!');
                    throw new ValidationException($validator);
                }
                $order->description = $request->description;
                $order->status = $request->status;
                $order->save();
                $this->reportOrder($order, 2);
                return redirect()->back()->with('success-alert', 'سفارش به وضعیت در انتظار پرداخت تغییر یافت');

            } elseif ($request->status == 4) { //send
                if (!in_array($order->shop_id, auth()->user()->shopIds())) {
                    $validator = Validator::make([], []); // Empty data and rules fields
                    $validator->errors()->add('common', 'شما مالک فروشگاه نیستید!');
                    throw new ValidationException($validator);
                }
                $request->validate([
                    'post_trace' => 'nullable|digitsbetween:1,50',

                ], [
                    'post_trace.digitsbetween' => 'کد رهگیری پستی حداکثر 50 است',
                ]);
                $order->status = $request->status;
                $order->send_at = Carbon::now();
                $order->post_trace = $request->post_trace;
                foreach ($order->products as $product) {
                    $product->sold = $product->sold + $product->pivot->qty;
                    $product->save();
                }
                $order->save();
                $this->reportOrder($order, 4);
                return redirect()->back()->with('success-alert', 'سفارش به وضعیت ارسال شده تغییر یافت');

            } elseif ($request->status == 3) { //user payed
                $request->validate([
                    'pay_id' => 'required|digitsbetween:1,100',
                    'status' => 'required|in:1,2,3,4,5',
                    'description' => 'nullable|string|max:1024',
                    'id' => 'required|numeric|' . Rule::in(Order::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->pluck('id')),
                ], [
                    'pay_id.required' => 'شناسه پرداخت ضروری است',
                    'pay_id.digitsbetween' => 'طول شناسه پرداخت حداکثر 100 است',
                    'description.required' => 'توضیحات ضروری است',
                    'description.string' => 'توضیحات متنی باشد',
                    'description.max' => 'حداکثر طول توضیحات 1024 کلمه باشد. طول فعلی: ' . mb_strlen($request->description),
                ]);
                $order->status = $request->status;
                $order->pay_id = $request->pay_id;
                $order->description = $request->description;
                $order->save();
                $this->reportOrder($order, 3);
                return redirect()->back()->with('success-alert', 'سفارش شما به وضعیت آماده ارسال تغییر یافت');

            }

        }

        $this->authorize('edit', [Order::class, $request->user_id, $request->shop_id, $request->status]);

        $request->validate([
            'status' => 'required|in:1,2,3,4,5',
            'name' => 'required|string|max:50',
            'phone' => 'required|digitsbetween:9,20',
            'description' => 'nullable|string|max:1024',
            'address' => 'required|string|max:500',
            'county_id' => 'required|' . Rule::in(County::pluck('id')),
            'province_id' => 'required|in:' . County::where('id', $request->county_id)->firstOrNew()->province_id,
            'postal_code' => 'nullable|numeric|digitsbetween:0,20',
            'post_price' => 'required|numeric|min:0|digitsbetween:1,10',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|' . Rule::in(Product::where('shop_id', $request->shop_id)->pluck('id')),
            'products.*.qty' => 'required|numeric|min:1|digitsbetween:1,10',
            $order->user_id != auth()->user()->id ? 'products.*.unit_price' : '' => $order->user_id != auth()->user()->id ? 'required|numeric|digitsbetween:1,10' : "",
            'id' => 'required|numeric|' . Rule::in(Order::where('user_id', $request->user_id)->where('shop_id', $request->shop_id)->pluck('id')),
        ], [
            'name.required' => 'نام گیرنده ضروری است',
            'name.max' => 'حداکثر طول 50 کلمه باشد. طول فعلی: ' . mb_strlen($request->name),
            'phone.required' => 'شماره گیرنده ضروری است',
            'phone.numeric' => 'شماره تماس عددی باشد',
            'phone.digitsbetween' => 'شماره تماس بین ۹ تا ۲۰ عدد باشد',
            'description.required' => 'توضیحات ضروری است',
            'description.string' => 'توضیحات متنی باشد',
            'description.max' => 'حداکثر طول توضیحات 1024 کلمه باشد. طول فعلی: ' . mb_strlen($request->description),
            'address.required' => 'آدرس ضروری است',
            'address.string' => 'آدرس متنی باشد',
            'address.max' => 'حداکثر طول آدرس 500 کلمه باشد. طول فعلی: ' . mb_strlen($request->address),
            'county_id.required' => 'شهر ضروری است',
            'county_id.in' => 'شهر نامعتبر است',
            'province_id.required' => 'استان ضروری است',
            'province_id.in' => 'استان با شهر تطابق ندارد',
            'postal_code.numeric' => 'کد پستی عددی باشد',
            'postal_code.digitsbetween' => 'کد پستی حداکثر ۲۰ عدد باشد',
            'products.min' => 'حداقل یک محصول ضروری است',
            'post_price.numeric' => 'هزینه پست عددی باشد',
            'post_price.min' => 'هزینه پست حداقل صفر باشد',
            'post_price.digitsbetween' => 'هزینه پست حداکثر 10 عدد باشد',
            'products.*.product_id.in' => 'شناسه محصول نامعتبر است',
            'products.*.unit_price.required' => 'قیمت واحد ضروری است',
            'products.*.unit_price.numeric' => 'قیمت واحد عددی است',
            'products.*.unit_price.digitsbetween' => 'قیمت واحد بین 1 و 10 رقم است',
            'products.*.qty.required' => 'تعداد ضروری است',
            'products.*.qty.numeric' => 'تعداد عددی است',
            'products.*.qty.min' => 'تعداد حداقل 1 است',
            'products.*.qty.digitsbetween' => 'تعداد بین 1 و 10 رقم است',
        ]);


        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->description = $request->description;
        $order->address = $request->address;
        $order->county_id = $request->county_id;
        $order->province_id = $request->province_id;
        $order->postal_code = $request->postal_code;
        $order->products()->sync($request->products);

        if ($order->user_id == auth()->user()->id) { //user order
            if ($order->status == 1 || $order->status == 2)
                $order->status = 1;
        } elseif (in_array($order->shop_id, auth()->user()->shopIds())) { //owner
            if ($order->status == 1 || $order->status == 2)
                $order->status = 2;
        }

        $total = 0;
        foreach ($order->products as $item) {
            $total += ($item->pivot->qty * $item->pivot->unit_price);
        }
        $order->total_price = $total + $order->post_price;
        $this->reportOrder($order, $order->status);
        $order->save();
        return response()->json('سفارش با موفقیت ویرایش شد', 200);
//        return redirect()->to('panel/my-orders')->with('success-alert', 'سفارش با موفقیت ویرایش شد');

    }

    public function create(Request $request)
    {
        $this->authorize('create', [Order::class]);

        $cart = Cart::get();
        $name = $request->name;
        $address = $request->address;
        $postalCode = $request->postal_code;
        $phone = $request->phone;
        $description = $request->description;
        $provinceId = $request->province_id;
        $countyId = $request->county_id;

        $request->validate([
            'name' => 'required|max:50',
            'address' => 'required|max:500',
            'postal_code' => 'nullable|numeric|digits_between:1,20',
            'phone' => 'required|max:20',
            'description' => 'nullable|max:1024',
            'province_id' => 'required|numeric|min:1|max:31',
            'county_id' => 'required|numeric|min:1|max:429',


        ], [
            'name.required' => 'نام دریافت کننده ضروری است',
            'name.max' => 'حداکثر طول 50 کلمه باشد. طول فعلی: ' . mb_strlen($name),
            'address.required' => 'آدرس دریافت کننده ضروری است',
            'address.max' => 'حداکثر طول 500 کلمه باشد. طول فعلی: ' . mb_strlen($address),
            'postal_code.digits_between' => 'حداکثر طول 20 باشد. طول فعلی: ' . mb_strlen($postalCode),
            'postal_code.numeric' => 'کد پستی عدد باشد',
            'phone.required' => 'تلفن دریافت کننده ضروری است',
            'phone.max' => 'حداکثر طول 20 کلمه باشد. طول فعلی: ' . mb_strlen($phone),
            'description.max' => 'حداکثر طول 1024 کلمه باشد. طول فعلی: ' . mb_strlen($description),
            'province_id.required' => 'استان دریافت کننده ضروری است',
            'province_id.min' => 'استان دریافت کننده نا معتبر است',
            'province_id.max' => 'استان دریافت کننده نا معتبر است',
            'county_id.required' => 'شهر دریافت کننده ضروری است',
            'county_id.min' => 'شهر دریافت کننده نا معتبر است',
            'county_id.max' => 'شهر دریافت کننده نا معتبر است',

        ]);
        if (Cart::count() == 0) {
            $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('cart', 'سبد خرید شما خالی است!');
            throw new ValidationException($validator);
        }
        foreach ($cart as $shop_id => $shop) {

            $order = Order::create([
                'user_id' => auth()->user()->id,
                'shop_id' => $shop_id,
                'status' => 2,
                'name' => $name,
                'address' => $address,
                'postal_code' => $postalCode,
                'phone' => $phone,
                'post_price' => null,
                'description' => $shop['desc'] ?? '',
                'province_id' => $provinceId,
                'county_id' => $countyId,


            ]);
            $totalPrice = 0;
            foreach ($shop['prods'] as $idx => $item) {
                $pPrice = Product::find($idx);
                $pPrice = $pPrice->discount_price ?: $pPrice->price ?: null;
                if ($pPrice === null)
                    $totalPrice = null;
                if ($pPrice !== null && $totalPrice !== null)
                    $totalPrice += $pPrice * $item['qty'];

                $order->products()->attach($idx, ['qty' => $item['qty'], 'unit_price' => $pPrice]);
            }
            $order->total_price = $totalPrice;
            $order->save();

            Cart::clear();

            $this->reportOrder($order, 0);
        }
        return redirect()->to('panel/my-orders/search?status=2')->with('success-alert', 'سفارش شما با موفقیت ثبت شد. بزودی با شما تماس می گیریم');
    }

    public
    function groups(Request $request)
    {
//        $user = auth()->user();
//        $query = Order::query();
//
//        if ($user->role == 'us') {
//            $query = $query->where('user_id', $user->id);
//        } elseif ($user->role == 'ad') {
//            $shops = Shop::where('user_id', $user->id)->pluck('id');
//            $query = $query->whereIn('shop_id', $shops);
//        }
//
//        $orders = $query->selectRaw('status,COUNT(status) AS count')->groupBy('status')->get();
        return view('pages.panel'/*, ['orders' => $orders]*/);
    }

    private function reportOrder($order, $status = 0)
    {
        $title = '✅ سفارش جدید';
        if ($status == 0)
            $title = '✅ یک سفارش جدید دارید! لطفا از پنل سایت بررسی نمایید.';
        if ($status == 1)
            $title = '✅ یک سفارش تغییر داده شده دارید! لطفا از پنل سایت بررسی نمایید.';
        if ($status == 2)
            $title = '✅ سفارش شما تایید شده و آماده پرداخت می باشد. این سفارش تا 24 ساعت پس از این پیام معتبر است';
        if ($status == 3)
            $title = '✅ یک سفارش پرداخت شده است! لطفا از طریق پنل سفارش را تایید و ارسال نمایید';
        if ($status == 4)
            $title = '✅ سفارش شما آماده و به آدرس شما ارسال شد. از خرید شما متشکریم!';

        $txt = $this->getOrderText($order);
        $txt .= PHP_EOL . "🆅🅰🆁🆃🅰🆂🅷🅾🅿" . PHP_EOL . Helper::$site . PHP_EOL;
        if ($status == 0 || $status == 1 || $status == 3) {
            foreach (\Helper::$logs as $log) {
                sendTelegramMessage($log, $title . PHP_EOL . $txt);
            }
        } elseif ($status == 2 || $status == 4) {
            $user = User::find($order->user_id);
            if ($user) {
                if ($user->telegram_id)
                    sendTelegramMessage($user->telegram_id, $txt);
                if ($user->email)
                    Mail::to($user->email)->queue(new OrderMail($title, str_replace("\n", "<br>", $txt), $order->id));
            }
        }
    }

    private function getOrderText($order)
    {
        $txt =
            "📌 شناسه سفارش: " . "#$order->id" . PHP_EOL .
            "🔑 شناسه پرداخت: " . "$order->pay_id" . PHP_EOL .
            "📨 کد رهگیری پست: " . "$order->post_trace" . PHP_EOL .
            "👤 نام مشتری: " . $order->name . PHP_EOL .
            "⏰ تاریخ ثبت: " . \Morilog\Jalali\Jalalian::fromDateTime($order->created_at ?? Carbon::now('Asia/Tehran'))->format('%A, %d %B %Y ⏰ H:i') . PHP_EOL .
            "🏩 فروشنده: " . $order->shop->name . PHP_EOL .

            "📰 توضیح مشتری: " . implode('🔹', explode('$|$', $order->description ?? '')) . PHP_EOL .
            "📭 آدرس: " . ($order->province->name ?? '') . " - " . ($order->county->name ?? '') . " - " . $order->address . PHP_EOL .
            "📤 کد پستی: " . $order->postal_code . PHP_EOL .
            "📪 هزینه پست: " . number_format($order->post_price) . " ت " . PHP_EOL .
            "💵 مجموع پرداختی: " . number_format($order->total_price) . " ت " . PHP_EOL .
            "📱 تلفن: " . $order->phone . PHP_EOL .
            "";
        foreach ($order->products as $product) {
            $txt .=
                "   📦 نام محصول: " . $product->name . PHP_EOL .
                "   🔹 تعداد: " . $product->pivot->qty . PHP_EOL .
                "   💵 قیمت واحد: " . number_format($product->pivot->unit_price) . " ت " . PHP_EOL .
                "";
        }
        return $txt;
    }
}
