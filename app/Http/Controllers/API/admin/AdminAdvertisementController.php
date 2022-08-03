<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;

class AdminAdvertisementController extends Controller
{
    public function pendingRequest(){
        $advertisements=Advertisement::where('control','pending')->with('user')->get();
            return response()->json(['pending_advertisement' => $advertisements]);


    }

    public function acceptedRequest(){
        $advertisements=Advertisement::where('control','accepted')->with('user')->get();
        return response()->json(['accepted_advertisement' => $advertisements]);
    }

    public function declinedRequest(){
        $advertisements=Advertisement::where('control','declined')->with('user')->get();
        return response()->json(['deslined_advertisement' => $advertisements]);
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
            return response()->json(['message'=>'تم قبول الاعلان بنجاح','advertisement'=>$advertisement]);
        }
    }

    public function rejectedRequest(Advertisement $advertisement_id){
        $advertisement=Advertisement::with('user')->find($advertisement_id->id);
        if ($advertisement->control == 'pending'){
            $advertisement->control='declined';
            $advertisement->save();
            return response()->json(['message'=>'تم رفض الاعلان بنجاح','advertisement'=>$advertisement]);
        }
    }
}
