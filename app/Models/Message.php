<?php

namespace App\Models;

use Illuminate\Cache\Events\RetrievingKey;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function sender(){
        return $this -> belongsTo(User::class);
    }

    public function receiver(){
        return $this ->belongsTo(User::class);
    }
}
