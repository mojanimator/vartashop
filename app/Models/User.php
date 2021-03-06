<?php

namespace App\Models;


//use App\Notifications\MyResetPassword;
use App\Notifications\MyResetPassword;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Morilog\Jalali\Jalalian;
//use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements /*Auditable,*/
    CanResetPassword
{

    use \Illuminate\Auth\Passwords\CanResetPassword;
    use Notifiable;
    use HasApiTokens;

    // use \OwenIt\Auditing\Auditable;

//    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';
    protected $fillable = [
        'id', 'name', 'username', 'telegram_username', 'email', 'email_verified', 'phone_verified', 'phone', 'telegram_id', 'img', 'role', 'password', 'img', 'score', 'step',
        'expires_at', 'created_at', 'updated_at', 'active', 'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'score' => 'integer',
        'verified' => 'bool',
        'must_join' => 'array',
        'channels' => 'array',
        'groups' => 'array',
        'allowed_games_limit' => 'integer',

    ];

    public function getExpiresAtAttribute($value)
    {
        if (!$value) return $value;
        return \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($value));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function findForPassport($username)
    {
        $fieldType =/* filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' :*/
            'telegram_username';
//        dd(User::where($fieldType, $username)->first());
        return
            User::where($fieldType, $username)->first();
    }

    public function hasShop()
    {

        if ($this->role == 'go')
            return true;
        return Shop::orWhere('user_id', $this->id)->orWhereIn('id', Rule::where('user_id', $this->id)->pluck('shop_id'))->exists();
    }

    public function shopIds()
    {

        if ($this->role == 'go')
            return Shop::pluck('id')->toArray();
        return Shop::orWhere('user_id', $this->id)->orWhereIn('id', Rule::where('user_id', $this->id)->pluck('shop_id'))->pluck('id')->toArray();
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }
}
