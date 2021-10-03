<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{

    protected $fillable = ['name', 'province_id'];
    protected $table = 'county';

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
