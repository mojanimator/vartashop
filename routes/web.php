<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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
Route::post('/chat/broadcast', [App\Http\Controllers\PusherController::class, 'broadcast'])->name('chat.broadcast');
Route::post('/chat/chatsupporthistory', [App\Http\Controllers\PusherController::class, 'chatSupportHistory'])->name('chat.support.history');


Auth::routes();
Route::get('/verifyemail/{token}/{from}', [App\Http\Controllers\Auth\RegisterController::class, 'verifyEmail'])->name('verification.mail');
Route::get('/resendemail/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'resendEmail'])->name('resend.mail');

Route::post('/user/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');


Route::get('/test', function () {
//    return DB::connection(env('DB_CONNECTION'))->getPdo();
    foreach (Product::get() as $item) {
        $item->slug = str_replace(['%', ' ', "\n"], ['درصد', '-', '-'], $item->name);
        $item->save();
    }
    return Product::on(env('DB_CONNECTION'))->get();
});

Route::get('/blog', function () {
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
            break;
    }

})->name('cart.edit');


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');

Route::post('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
Route::post('/order/delete', [App\Http\Controllers\OrderController::class, 'delete'])->name('order.delete');

Route::middleware(['auth', 'verify'])->prefix('panel')->group(function () {


    Route::get('', function () {
        return view('pages.panel');
    })->name('panel.view');

//    Route::get('my-orders', [App\Http\Controllers\OrderController::class, 'groups'])->name('panel.my-orders');


    Route::get('{route?}/{route2?}', function () {
        return view('pages.panel');
    })->name('panel.view');


});

//firebase
Route::post('/save-token', 'FCMController@index');