<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

//    public $timestamps = false;
    protected $table = 'rules';
    protected $fillable = [
        'id', 'user_id', 'shop_id', 'access_type',
    ];

}