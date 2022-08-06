<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;



class CommentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adsvertisement_id ;
    public $renter_id ;
    public $content ;
    public $time ;
    public $review_comment;
    public $review_count ;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($Notification_comment_data)

    {

        $this->adsvertisement_id = $Notification_comment_data["adsvertisement_id"];
        $this->renter_id = $Notification_comment_data[ "renter_id"];


        $this->content = $Notification_comment_data["content"];

        $this->time = $Notification_comment_data["time"];

        $this->review_comment = $Notification_comment_data["review_comment"];

        $this->review_count = $Notification_comment_data["review_count"];

        



        

         
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    
    {
        return ['NewChannel'];
        // return new Channel('NewCommentNotification');
    }

    public function broadcastAs()
  {
    return "CommentNotification";
    //   return    event(new CommentNotification($Notification_comment_data));

  }
}
