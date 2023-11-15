<?php
/**
 * Created by PhpStorm.
 * User: MSI GS72
 * Date: 02/08/2021
 * Time: 07:39 PM
 */

namespace App\Models;


use Illuminate\Support\Facades\Session;

class Cart
{

    public function __construct()
    {
//        $cart = session('cart');
    }

    static function clear()
    {
        $cart = session()->forget('cart');

    }

    static function getShopDesc($shop_id)
    {
        $cart = session()->has('cart') ? session('cart') : [];
        if (!isset($cart[$shop_id]) || !isset($cart[$shop_id]['desc'])) return null;
        return $cart[$shop_id]['desc'];

    }

    static function setShopDesc($descs)
    {
        $cart = session()->has('cart') ? session('cart') : [];
        foreach ($descs as $idx => $desc) {

//            if (!isset($cart[$idx]))
//                $cart[$idx] = ['desc' => $desc];
//            else
            $cart[$idx]['desc'] = $desc;
        }
        session()->put('cart', $cart);

    }

    static function get()
    {
        $cart = session()->has('cart') ? session('cart') : [];
        return $cart;
    }

    static function remove($id)
    {
        $cart = session()->has('cart') ? session('cart') : [];
        foreach ($cart as $i => $shop) {

            if (isset($cart[$i]['prods'][$id])) {
                unset($cart[$i]['prods'][$id]);
                if (count($cart[$i]['prods']) == 0)
                    unset($cart[$i]);
            }

        }

        session()->put('cart', $cart);
    }

    static function plus($id, $shop_id)
    {

        $cart = session()->has('cart') ? session('cart') : [];

        if (isset($cart[$shop_id]['prods'][$id])):
            $cart[$shop_id]['prods'][$id]['qty'] += 1;
        else:
            $cart[$shop_id]['prods'][$id] = ['qty' => 1];

        endif;
        session()->put('cart', $cart);

//        dd($cart);
    }

    static function minus($id, $shop_id)
    {


        $cart = session()->has('cart') ? session('cart') : [];
//        if (!isset($cart[$shop_id]))
//            $cart[$shop_id] = [];
        if (isset($cart[$shop_id]['prods'][$id]) && $cart[$shop_id]['prods'][$id]['qty'] > 1):
            $cart[$shop_id]['prods'][$id]['qty'] -= 1;
        else:
            $cart[$shop_id]['prods'][$id] = ['qty' => 1];

        endif;
        session()->put('cart', $cart);

//        dd($cart);
    }

    static function count($id = null, $shop_id = null)
    {
        $cart = session('cart');
//        $cart = session()->forget('cart');

        if (!$cart) {
            return 0;
        } elseif ($id != null) {

            foreach ($cart as $idx => $shop) {
                if (isset($shop['prods'][$id]))
                    return $shop['prods'][$id]['qty'];
            }
            return 0;

        } elseif ($shop_id != null) {

            if (!isset($cart[$shop_id]['prods'])) return 0;
            $qty = 0;
            foreach ($cart[$shop_id]['prods'] as $product) {
                $qty += $product['qty'];
            }
            return $qty;

        } else {
            $qty = 0;
            foreach ($cart as $shop) {
                foreach ($shop['prods'] as $product) {
                    $qty += $product['qty'];
                }
            }

            return $qty;
        }

    }
}
