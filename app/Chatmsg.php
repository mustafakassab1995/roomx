<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatmsg extends Model
{
    //
    protected $fillable = [
        'id', 'sdr_id', 'rcv_id', 'chatpeople_id', 'msg' ,'seen'
    ];
    public function user(){
        return $this->belongsToMany('\App\User');
    }
    
}
