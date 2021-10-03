<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

    protected $appends = ['image'];
    public $timestamps = false;
    protected $table = 'shops';
    protected $fillable = [
        'id', 'user_id', 'channel_address', 'county_id', 'address', 'postal_code', 'page_address', 'site_address', 'name', 'description', 'group_id', 'timestamp', 'active', 'auto_tag', 'auto_channel_post', 'subscribe', 'created_at'
    ];
    protected $casts = [
        // 'chat_id' => 'string',
//        'expire_time' => 'timestamp',
//        'start_time' => 'timestamp',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }


    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageAttribute()
    {

//        $c = Chat::where('chat_id', $this->channel_address)->first();
//        if (!$c) return null;
        return asset('storage/shops/' . $this->id . '.jpg');
    }

    public function location($type = null)
    {

        if ($type == 'render') {
            $c = County::where('id', $this->county_id)->with('province:id,name')->first();
            return $c ? $c->name . ' -> ' . $c->province->name : null;
        } else
            return County::where('id', $this->county_id)->with('province:id,name')->first();
    }

}
