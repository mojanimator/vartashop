<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\VarDumper\Caster\ImgStub;

class Product extends Model
{
    protected $appends = ['salePercent'];
    public $timestamps = false;
    protected $table = 'products';
    protected $fillable = [
        'id', 'shop_id', 'group_id', 'props', 'name', 'description', 'count', 'tags', 'sold', 'price', 'discount_price', 'created_at'
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }


    public function user()
    {
        return User::where('id', Shop::where('id', $this->shop_id)->first()->user_id);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function image()
    {
        $img = Image::where('for_id', $this->id)->get('id')->first();
        if ($img)
            return asset("storage/products/" . $img->id . '.jpg');
        else {
            return asset("img/noimage.png");
        }
    }

    public function images()
    {
        return Image::where('for_id', $this->id)->pluck('id')->map(function ($data) {
            return asset("storage/products/" . $data . '.jpg');

        });
    }

    public function getSalePercentAttribute()
    {
        if (!$this->discount_price)
            return 0;
        return round(($this->price - $this->discount_price) * 100 / ($this->price)) . "%";
    }

//    public function getSlugAttribute()
//    {
//
//
//        return str_slug($this->name, $language = 'fa');
//    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
