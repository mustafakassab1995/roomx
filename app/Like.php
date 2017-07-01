<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    protected $fillable = [
        'room_id', 'user_id',
    ];
    public function user(){
        return $this->belongsTo('\App\User');
    }
    public function room(){
        return $this->belongsTo('\App\Room');
    }
}
