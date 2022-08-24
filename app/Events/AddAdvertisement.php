<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddAdvertisement implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $advertisement;
    public $message;
    public $time;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($add_advertisement_data)
    {
        $this-> advertisement = $add_advertisement_data["advertisement"];
        $this->message =$add_advertisement_data["message"];
        $this->time=$add_advertisement_data["time"];



    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()

    {
        return ['NewChannel3'];

    }

    public function broadcastAs()
    {
        return "AddAdvertisement";

    }
}
