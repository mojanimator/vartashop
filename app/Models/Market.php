<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;


    public $timestamps = true;
    protected $table = 'market';
    protected $fillable = [
        'id', 'name', 'name_fa',
    ];
    protected $casts = [
        // 'chat_id' => 'string',

    ];
}
