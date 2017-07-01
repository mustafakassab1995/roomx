<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'email', 'password', 'dob', 'phone', 'photolink', 'city', 'country','player_id'
    ];

    public function room(){
        return $this->hasMany('\App\Room');
    }
    public function room_reservation(){
        return $this->hasMany('\App\RoomReservation');
    }
    public function like(){
        return $this->hasMany('\App\Like');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
