<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\user;

use Illuminate\Support\Facades\Auth;


class GetNotificationController extends Controller
{
    public function view(){
        $content = [];
        $notification = Notification::where([["user_id", Auth::user()->id ], ["status","not_red"]])->with("user")->get();
        foreach ( $notification as $notifii){
            $content[ ] = $notifii->content;
        }
        $count =Notification::where([["user_id", Auth::user()->id ], ["status","not_red"]])->count();

        foreach($notification as $notify){
            $notify->status="red";
            $notify->save();
        }
        
        return response()->json(["notification" => $content , "count" =>  $count]);
        

    }

    
}
