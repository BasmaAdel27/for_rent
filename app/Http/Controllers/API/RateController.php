<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Rating;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\CommentNotification;
use Illuminate\Support\Carbon;
use App\Models\Notification;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Advertisement $advertisement_id)
    {
//        dd($advertisement);
        $validator=Validator::make($request->all(),[
            'count'=>'required|numeric|min:0|max:5',
        ],[
            'count.required'=>'برجاء ادخال تقييمك للاعلان'
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);

        }

        if (Rating::where([['advertisement_id',$advertisement_id->id],['user_id',\auth()->user()->id]])->exists()){
            $result=Rating::where([['advertisement_id',$advertisement_id->id],['user_id',\auth()->user()->id]])->delete();
        }

        


            $rate = new Rating();
            $rate->user_id = Auth::user()->id;
            $rate->comment = $request->comment;
            $rate->count = $request->count;
            $advertisement_id->ratings()->save($rate);



            $Notification_comment_data=[
                "adsvertisement_id" => $advertisement_id,
                "renter_id" => Auth::id(),
                "renter_name"=>Auth::user()->name,
                "content" => " تم اضافة مراجعه على الاعلان الخاص بك من قبل " . Auth::user()->name ,
                "time" => carbon::now(),
                "review_comment" => $request->comment,
                "review_count" => $request->count
            ];
            event(new CommentNotification($Notification_comment_data));

        //////////////////store notification//////////////////////
        $notification = New Notification ;
        $notification->user_id = $advertisement_id->user_id ;
        $notification->advertisement_id = $advertisement_id->id;
        $notification->content = $Notification_comment_data["content"];
        $notification->status = "not_red";
        $notification->sent_at =$Notification_comment_data["time"];
        $notification->save();
           


            return response()->json(['message'=>'تم تقييم الاعلان بنجاح','rate'=>$rate],200);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement_id,Rating $rate_id)
    {
//        dd($rate_id);
        if (Auth::user()->id != $rate_id->user_id){
            return response()->json(['message'=>'غير مصرح لك بالمسح'],404);
        }else {
            $rate_id->delete();
            abort(204);
        }
    }

}
