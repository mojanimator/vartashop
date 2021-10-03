<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function delete(Request $request)
    {


        $this->authorize('delete', [Order::class, $request->order_user_id, $request->shop_id]);

        Order::where('id', $request->order_id)->delete();
        return redirect()->back();
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
                'description' => substr($shop['desc'], 0, 1000),
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
}
