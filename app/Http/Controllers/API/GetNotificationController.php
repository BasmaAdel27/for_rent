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
        $notification = Notification::where("user_id", Auth::user()->id )->with("user")->get();
        $count =Notification::where("user_id", Auth::user()->id)->count();

        foreach($notification as $notify){
            $notify->status="red";
            $notify->save();
        }
        
        return response()->json(["notification" => $notification , "count" =>  $count]);
        

    }

    
}
