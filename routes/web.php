<?php

use App\Models\Group;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', function () {
    return Storage::cloud()->listContents('/', false);
    Storage::disk('google')->get('test.txt');
//    Storage::disk('google')->put('test.txt', 'Hello World');
    return Group::orWhereIn('parent', [46])->orWhereIn('id', [46])->pluck('id');
    foreach (\App\Models\Order::get() as $order) {
        $txt = '✅ سفارش جدید' . PHP_EOL .
            "⏰ تاریخ ثبت: " . \Morilog\Jalali\Jalalian::fromDateTime($order->created_at)->format('%A, %d %B %Y ⏰ H:i') . PHP_EOL .
            "🏩 فروشنده: " . $order->shop->name . PHP_EOL .
            "📰 توضیح مشتری: " . $order->description . PHP_EOL .
            "📭 آدرس: " . $order->province->name . " - " . $order->county->name . " - " . $order->address . PHP_EOL .
            "📤 کد پستی: " . $order->postal_code . PHP_EOL .
            "💵 مجموع: " . number_format($order->total_price) . " ت " . PHP_EOL .
            "📱 تلفن: " . $order->phone . PHP_EOL .
            "";
        foreach ($order->products as $product) {
            $txt .=
                "🔮 نام محصول: " . $product->name . PHP_EOL .
                "🔹 تعداد: " . $product->pivot->qty . PHP_EOL .
                "💵 قیمت واحد: " . number_format($product->pivot->unit_price) . " ت " . PHP_EOL .
                "";

            return json_encode($txt);
        }
    }
});

Route::post('/chat/broadcast', [App\Http\Controllers\PusherController::class, 'broadcast'])->name('chat.broadcast');
Route::post('/chat/chatsupporthistory', [App\Http\Controllers\PusherController::class, 'chatSupportHistory'])->name('chat.support.history');


Auth::routes();
Route::get('/verifyemail/{token}/{from}', [App\Http\Controllers\Auth\RegisterController::class, 'verifyEmail'])->name('verification.mail');
Route::get('/resendemail/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'resendEmail'])->name('resend.mail');

Route::post('/user/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');


Route::get('/blog', function () {
    return redirect('charge');
    return view('pages/blog');
})->name('blog.view');

Route::get('/panel', function () {
    return view('pages/panel');
})->name('panel.view');

Route::get('/shops', function () {
    return view('pages/shops');
})->name('shops.view');

Route::get('/shop/{name}/{id}', function ($name, $id) {
    return view('pages/shop')->with(['shop' => \App\Models\Shop::findOrFail($id)]);
})->name('shop');

Route::get('/products', function () {
    return view('pages/products');
})->name('products.view');

Route::get('/product/search', [App\Http\Controllers\ProductController::class, 'search'])->name('product.search');
Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
Route::get('/product/images', [App\Http\Controllers\ProductController::class, 'images'])->name('product.images');

Route::get('/group/search', [App\Http\Controllers\ProductController::class, 'groups'])->name('group.search');

Route::get('/product/{slug}/{id}', function ($slug, $id) {

    return view('pages/product')->with('product', Product::findOrFail($id));
})->name('product.view');

Route::get('/tag/{tag}', function ($tag) {

    return view('pages/tag')->with('tag', $tag);
})->name('tag.view');

Route::get('/cart', function () {

    return view('pages/cart');
})->name('cart.view');

Route::post('/cart/edit', function () {

    $cmnd = request('cmnd');
    $id = request('id');
    $shop_id = request('shop_id');
    $descs = request('descs');

    switch ($cmnd) {
        case 'plus':
            \App\Models\Cart::plus($id, $shop_id);

            return redirect()->back()->with('success', 'با موفقیت به سبد خرید اضافه شد')
//                ->withHeaders([
//                'Cache-Control' => 'no-cache, no-store, must-revalidate',
//                'Pragma' => 'no-cache',
//                'Expires' => 0
//            ]);
                ;
            break;
        case 'minus':
            \App\Models\Cart::minus($id, $shop_id);
            return redirect()->back()->with('success', 'از سبد خرید حذف شد');
            break;
        case 'remove':
            \App\Models\Cart::remove($id);
            return redirect()->back()->with('success', 'از سبد خرید حذف شد');
            break;
        case 'setdescs':
            \App\Models\Cart::setShopDesc($descs);
            return $descs;
        case 'checkout':
            \App\Models\Cart::setShopDesc($descs);
            return $descs;

            break;
    }

})->name('cart.edit');


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');

Route::post('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
Route::post('/order/delete', [App\Http\Controllers\OrderController::class, 'delete'])->name('order.delete');
Route::post('/order/edit', [App\Http\Controllers\OrderController::class, 'edit'])->name('order.edit');
Route::post('/factor/create', [App\Http\Controllers\OrderController::class, 'pdf'])->name('factor.create');


Route::post('/shop/edit', [App\Http\Controllers\ShopController::class, 'edit'])->name('shop.edit');
Route::post('/shop/create', [App\Http\Controllers\ShopController::class, 'create'])->name('shop.create');
Route::post('/shop/delete', [App\Http\Controllers\ShopController::class, 'delete'])->name('shop.delete');

Route::middleware(['auth'])->prefix('panel')->group(function () {


    Route::get('', function () {
        return view('pages.panel');
    })->name('panel.view');

    Route::get('my-products/search', [App\Http\Controllers\ProductController::class, 'search']);


//    Route::get('my-orders', [App\Http\Controllers\OrderController::class, 'groups'])->name('panel.my-orders');

    Route::prefix('{route?}')->group(function () {

        Route::get('checkout', function () {

            return view('pages.panel');
        })->name('panel.view')->middleware('verify');

        Route::get('{route2?}', function () {

            return view('pages.panel');
        })->name('panel.view');


    });
});

//firebase
Route::post('/save-token', 'FCMController@index');


