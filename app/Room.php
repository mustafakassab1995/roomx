<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'name', 'user_id', 'number_of_guests', 'number_of_beds', 'number_of_baths', 'number_of_rooms', 'location', 'description', 'tv', 'wifi', 'pool','price', 'air_condition', 'kitchen','city' 
    ];


    public function user(){
        return $this->belongsTo('\App\User');
    }
    public function room_photo(){
        return $this->hasMany('\App\RoomPhoto');
    }
    public function room_reservation(){
        return $this->hasMany('\App\RoomReservation');
    }
    public function room_rating(){
        return $this->hasMany('\App\RoomRating');
    }
    public function like(){
        return $this->hasMany('\App\Like');
    }
}
