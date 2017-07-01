<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomRating extends Model
{
    //
    protected $fillable = [
        'rating', 'room_id' , 'user_id','comment'
    ];
    public function room(){
        return $this->belongsTo('\App\Room');
    }
}
