<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfirmOwnerRequestFromAdmin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $advertisement;
    public $time;
    public $user_id;
    public function __construct($confirm_notification_data )
    {
        $this->message = $confirm_notification_data["message"] ;
        $this->advertisement=$confirm_notification_data["advertisement"] ;
        $this->time=$confirm_notification_data ["time"];
        $this->user_id=$confirm_notification_data["user_id"];

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
    */
    public function broadcastOn()
    {
        return ['NewChannel2'];
    }

    public function broadcastAs()
  {
    return "ConfirmOwnerRequestFromAdmin";

  }

}
 
