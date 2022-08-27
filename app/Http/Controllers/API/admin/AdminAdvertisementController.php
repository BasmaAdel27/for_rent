<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Support\Carbon;
use App\Events\ConfirmOwnerRequestFromAdmin;
use App\Models\Notification;



class AdminAdvertisementController extends Controller
{
    public function pendingRequest(){
        $advertisements=Advertisement::where('control','pending')->with('user')->get();
        if($advertisements->isEmpty()) {
            return response()->json(['message'=>'لا يوجد اعلانات قيد الموافقه','counts'=>count($advertisements)]);
        }else{
            return response()->json(['pending_advertisement' => $advertisements,'counts'=>count($advertisements)]);

        }


    }

    public  function showPending($id)
    {
        $advertisement = Advertisement::where([['id', $id], ['control', 'pending']])->with('advertisement_image', 'user')->get();
        return response()->json([ 'advertisement' => $advertisement]);
    }

    public function acceptedRequest(){
        $advertisements=Advertisement::where('control','accepted')->with('user')->get();
        if($advertisements->isEmpty()) {
            return response()->json(['message'=>'لا يوجد اعلانات تمت الموافقه عليها','count'=>count($advertisements)]);
        }else{
            return response()->json(['accepted_advertisement' => $advertisements,'count'=>count($advertisements)]);

        }
    }

    public function declinedRequest(){
        $advertisements=Advertisement::where('control','declined')->with('user')->get();
        if($advertisements->isEmpty()) {
            return response()->json(['message'=>'لا يوجد اعلانات ملغيه','count'=>count($advertisements)]);
        }else{
            return response()->json(['deslined_advertisement' => $advertisements,'count'=>count($advertisements)]);

        }
    }

    public function destroy(Advertisement $advertisement_id){
        $advertisement=Advertisement::where('id',$advertisement_id->id)->delete();
        return response()->json(['message'=>'تم مسح الاعلان بنجاح']);

    }
    public function showRequest(Advertisement $advertisement_id){
        $advertisement=Advertisement::where('id',$advertisement_id->id)->with('advertisement_image','user')->get();
        return response()->json(['advertisement_details'=>$advertisement]);

    }

    public function confirmRequest(Advertisement $advertisement_id){


        $advertisement=Advertisement::with('user')->find($advertisement_id->id);
//        dd($advertisement->control);
        if ($advertisement->control == 'pending'){
            $advertisement->control='accepted';
            $advertisement->save();


            //fire_confirm event
            $confirm_notification_data = [

                "message"=>'تم قبول الاعلان بنجاح'.' : '. $advertisement->title,
                'advertisement'=>$advertisement->title,
                "user_id"=>$advertisement->user_id,
                "time" => carbon::now()
            ];
            event(new ConfirmOwnerRequestFromAdmin($confirm_notification_data ));

            //store notification
        $notification = New Notification ;
        $notification->user_id = $advertisement_id->user_id ;
        $notification->advertisement_id = $advertisement_id->id;
        $notification->content = $confirm_notification_data["message"] ;;
        $notification->status = "not_red";
        $notification->sent_at =$confirm_notification_data["time"];
        $notification->save();

            return response()->json(['message'=>'تم قبول الاعلان بنجاح','advertisement'=>$advertisement]);

        }

    }

    public function rejectedRequest(Advertisement $advertisement_id){
        $advertisement=Advertisement::with('user')->find($advertisement_id->id);
        if ($advertisement->control == 'pending'){
            $advertisement->control='declined';
            $advertisement->save();


            $reject_advertisement_data = [

                "message"=>'تم رفض الاعلان '.' : '. $advertisement->title,
                'advertisement'=>$advertisement->title,
                "user_id"=>$advertisement->user_id,
                "time" => carbon::now()
            ];
            event(new RejectAdvertisement($reject_advertisement_data ));

            return response()->json(['message'=>'تم رفض الاعلان بنجاح','advertisement'=>$advertisement]);
        }
    }
}
