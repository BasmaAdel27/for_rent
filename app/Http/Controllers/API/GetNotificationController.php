<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class GetNotificationController extends Controller
{
    public function view(){
        $notification = Notification::where("user_id", Auth::id() )->get();
        foreach($notification as $notify){
            $notify->status="red";
            $notify->save();
        }
        
        return response()->json($notification);
        

    }
}
