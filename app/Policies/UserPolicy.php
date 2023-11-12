<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {

        if ($user->role == 'Go') {
            return true;
        }
    }


    public function ownShop(User $user, $shop_id, $abort)
    {
        if ($user->role == 'go')
            return true;
        if (\App\Models\Shop::where('user_id', $user->id)->exists() || \App\Models\Rule::where('user_id', $user->id)->where('shop_id', $shop_id)->exists())
            return true;
        if ($abort)
            return abort(403, 'این فروشگاه متعلق به شما نیست!');
        else return false;
    }

    public function ownProduct(User $user, $product_id, $abort)
    {
        if ($user->role == 'go')
            return true;

        $shop_id = Product::findOrNew($product_id)->shop_id;

        if (\App\Models\Shop::where('user_id', $user->id)->exists() || \App\Models\Rule::where('user_id', $user->id)->where('shop_id', $shop_id)->exists())
            return true;
        if ($abort)
            return abort(403, 'این محصول متعلق به شما نیست!');
        else return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
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
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return false;
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
