<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatpeople extends Model
{
    //
    protected $fillable = [
        'id', 'user1_id', 'user2_id'
    ];
    public function user(){
        return $this->belongsTo('\App\User');
    }
     public function chatmsg(){
        return $this->hasMany('\App\Chatmsg');
    }
    
}
