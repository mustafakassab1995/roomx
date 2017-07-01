<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    //
     protected $fillable = [
        'photolink', 'room_id'
    ];
    public function room(){
        return $this->belongsTo('\App\Room');
    }
}
