<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Payment;
use App\Models\Paymentmethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PaymentNotification;
use App\Models\Notification;



use App\Models\User;

use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
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
    public function payment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        Charge::create ([
            "amount" => $request->price * 100,
            "currency" => "usd",
            "source" => $request->token,
            "description" => "Test payment for booking "
        ]);

        $payment=Paymentmethod::create([
            'user_id'=>Auth::user()->id,
            'owner_id'=>$request->owner_id,
            'advertisement_id'=>$request->adver_id,
            'amount'=>$request->price,

        ]);
       $payment->created_at=Carbon::now()->toDateString();
        $payment->expired_at=Carbon::now()->addDays(30)->toDateString();
        $payment->save();
        $advertisement=Advertisement::where([['id',$payment->advertisement_id],['control','accepted'],['user_id',$payment->owner_id]])->first();
        $advertisement->status='rented';
        $advertisement->save();

         //start payment notification
        $user_id = Auth::user()->id;
        $owner_id = $request->owner_id;
        $user = User::find($user_id);
        $renter_name = $user->name;
        $payment_notf_data = [

            "message" => "تم الدفع من خلال الموقع من المستاجر" . ': '. $renter_name . "  ". " للعقار : " . $advertisement->title ,
            
            "owner_id"=> $owner_id,
           
        ];
        event(new PaymentNotification( $payment_notf_data ));
        $notification = New Notification ;
        $notification->user_id =  $owner_id;    //ADMIN ID
        $notification->advertisement_id = $advertisement->id;
        $notification->content = $payment_notf_data["message"];
        $notification->status = "not_red";
        $notification->sent_at =carbon::now();
        $notification->save();


        return  response()->json(['success'=>true,'advertisement'=>$advertisement,'payment'=>$payment]);
    }
   



    public function renterPayment(){
        $data=Paymentmethod::where('user_id',Auth::user()->id)->with('user','owner')->get();

        if($data->isEmpty()) {
            return response()->json(['message'=> 'لا توجد اعلانات مؤجره','count'=>count($data)]);

        }else{
            $advertisements=[];
            foreach ($data as $value){

                $advertisements[]=Advertisement::where('id',$value->advertisement_id)->where('status','rented')->with('payment')->withCount('ratings')->withAvg("ratings", "count")->with('favourit',"advertisement_image")->get();
                $date=date('d-m-Y', strtotime($value->created_at));
                $expire=date('d-m-Y', strtotime($value->expired_at));
            }
            return response()->json(["allAdvertisements" => $advertisements,'date'=>$date,'expired'=>$expire,'count'=>count($advertisements)]);

        }
    }

    public function ownerPayment(){
        $data=Paymentmethod::where('owner_id',Auth::user()->id)->with('advertisement.advertisement_image','user','owner')->get();
        if($data->isEmpty()) {
            return response()->json(['message'=> 'لا توجد اعلانات مؤجره','count'=>count($data)]);

        }else{
            return response()->json(['success'=>true,'data'=>$data,'count'=>count($data)]);

        }
    }

    public function paymentAdmin(){
        $data=Paymentmethod::with('advertisement.advertisement_image','user','owner')->get();
        if($data->isEmpty()) {
            return response()->json(['message'=> 'لا توجد اعلانات مؤجره','count'=>count($data)]);
        }else{
            return response()->json(['success'=>true,'data'=>$data,'count'=>count($data)]);

        }

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
    public function destroy($id)
    {
        //
    }
}
