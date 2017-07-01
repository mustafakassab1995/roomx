<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
  class Test extends Event implements ShouldBroadcast
    {
        /**
         * @var array
         */
        public $data;

        /**
         * Create a new event instance.
         *
         * @param mixed $data
         */
        public function __construct($data)
        {
            $this->data = $data;
        }

        /**
         * Get the channels the event should be broadcast on.
         *
         * @return array
         */
        public function broadcastOn()
        {
            return ['test-channel-name'];
        }
        public function h(){
   \Nexmo::message()->send([
    'to' => '970592414345',
    'from' => 'Roomxapi',
    'text' => 'Using the facade to send a mesage.'
]);
    }
    //     public function h(){
    //     	 //In your BLL
    // \Event::fire(new Test(['param1' => 'value']));
    // //
    // \Event::fire(new Test(123));
    //     }
    }

    
   