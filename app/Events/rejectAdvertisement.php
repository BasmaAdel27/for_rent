<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class rejectAdvertisement implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reject_advertisement_data)
    {
        $this-> advertisement = $reject_advertisement_data["advertisement"];
        $this->message =$reject_advertisement_data["message"];
        $this->time=$reject_advertisement_data["time"];



    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()

    {
        return ['NewChannel4'];

    }

    public function broadcastAs()
    {
        return "RejectAdvertisement";

    }
}

