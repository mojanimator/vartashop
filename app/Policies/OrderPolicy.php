<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Rule;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Response;


class OrderPolicy
{
    use HandlesAuthorization;


    /**
     * OrderPolicy constructor.
     */


    public function before(User $user, $ability)
    {
        if ($user->role == 'go') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $link = url('panel/user-settings');
        $link = "<a href='$link' class='text-success hoverable-text-dark'>حساب کاربری</a>";

        if (!$user->email_verified && !$user->phone_verified) {
            return abort(403, "ابتدا ایمیل و شماره تلفن خود را از قسمت $link تایید کنید");

        }
        if (!$user->phone_verified) {
            return abort(403, "ابتدا شماره تلفن خود را از قسمت $link تایید کنید");

        }

        if (!$user->email_verified) {
            return abort(403, "ابتدا ایمیل  خود را از قسمت $link تایید کنید");

        }
        if (!$user->active) {
            return abort(403, "حساب کاربری شما غیر فعال است");

        }

        return true;

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Order $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete($user, $order_user_id, $order_shop_id, $order_status)
    {
        //status <3
        //user is go
        //user is shop owner
        //user is shop owner
        //user is order owner

        if ($order_status > 2 && $user->role != 'go')
            return abort(403, 'مجاز به حذف سفارش های آماده ارسال و ارسال شده نیستید!');
        if ($order_user_id != $user->id
            && $user->role != 'go'
            && !Shop::where('id', $order_shop_id)->where('user_id', $user->id)->exists()
            && !Rule::where('user_id', $user->id)->where('shop_id', $order_shop_id)->exists())


            return abort(403, 'مجاز به حذف این سفارش نیستید!');
        return true;
    }

    public function edit($user, $order_user_id, $order_shop_id, $order_status)
    {
        //status <3
        //user is go
        //user is shop owner
        //user is order owner

        if ($order_status > 3 && $user->role != 'go')
            return abort(403, 'مجاز به ویرایش سفارش های  ارسال شده نیستید!');
        if ($order_user_id != $user->id
            && $user->role != 'go'
            && !Shop::where('id', $order_shop_id)->where('user_id', $user->id)->exists()
            && !Rule::where('user_id', $user->id)->where('shop_id', $order_shop_id)->exists())


            return abort(403, 'مجاز به ویرایش این سفارش نیستید!');
        return true;
    }


    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
