<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    protected $fillable = [
        'name',
    ];
    protected $table = 'province';

    public function counties()
    {
        return $this->hasMany(County::class, 'province_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'province_id');
    }
}
