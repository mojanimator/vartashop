<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $appends = [];
    public $timestamps = true;
    protected $table = 'orders';
    //1:unready 2:in process 3:ready for send 4:sent
    protected $fillable = [
        'id', 'user_id', 'shop_id', 'status', 'name', 'county_id', 'province_id', 'address', 'postal_code', 'phone', 'post_price', 'total_price', 'description', 'created_at', 'updated_at', 'send_at',
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('qty', 'unit_price');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function county()
    {
        return $this->belongsTo(County::class, 'county_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
