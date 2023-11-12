<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

// channels and groups in active countdown state
class Channel extends Model
{
    public $timestamps = false;
    protected $table = 'channels';
    protected $fillable = [
        'user_id', 'shop_id', 'chat_id', 'chat_username',
        'auto_tag', 'tag', 'active', 'auto_msg_day', 'auto_msg_night', 'auto_fun'
    ];
    protected $casts = [
        // 'chat_id' => 'string',
        //'expire_time' => 'timestamp',

    ];
}