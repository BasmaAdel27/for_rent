<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

use App\Models\Advertisement;
use App\Events\AddAdvertisement;
use Illuminate\Support\Carbon;





class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
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
    public function create(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'title' => 'required|string|min:10|unique:advertisements|max:200',
            'description' => 'required|string|min:20|max:300',
            'price'=>'required|numeric',
            'bedroom_num'=>'required|numeric',
            'bathroom_num'=>'required|numeric',
            	
            'beds_num'=>'required|numeric',
            'level'=>'required|numeric',
            'type'=>'required',
            'status' => "required",
            'area'=>['required','numeric','regex:/^([1-9][0-9]{0,2}|1000)$/'],
            'furniture'=>"required",
            'address' => 'required|max:40|min:10|string',
            'Latitude'=>'required|numeric',
            'Longitude' =>'required|numeric',
        ],[
            'title.required' =>'برجاء ادخال عنوان الاعلان',
            'title.unique' => 'يجب ان يكون اسم الاعلان مميز و غير مكرر يرجاء اخال عنوان اخر ',
            'title.min' => 'برجاء ادخال عنوان لا تقل حروفه عن 20 حرف ',
            'title.max' => 'اقصى حد للعنوان هو 200 حرف',


            "description.required" => "وصف الاعلان مطلوب",
            "description.min" => "برجاء ادخال عدد حروف لا تقل عن 20 حرف لوصف الاعلان",
            "description.max" => "اقصي حد للحروف المطلو ادخالها هو 300 حرف",


            'price.required' => "برجاء ادخال السعر ",
            'price.numeric' => 'برجاء ادخال رقم ',

            'bedroom_num.required' => 'برجاء ادخال عدد غرف النوم ',
            'bedroom_num.numeric' => 'برجاء ادخال رقم ',
            'bathroom_num.required' => 'برجاء ادخال عدد غرف الحمام ',
            'bathroom_num.numeric' => 'برجاء ادخال رقم ',
            'bedroom_num.required' => 'برجاء ادخال عدد غرف النوم الخاصه بالاعلان',
            'bedroom_num.numeric' => 'برجاء ادخال رقم',
            'beds_num.required' => 'برجاء ادخال عدد الاسره الموجوده بالاعلان',
            'beds_num.numeric' => 'برجاء ادخال رقم ',
            'level.required' => 'برجاء ادخال رقم الدور',
            'level.numeric' => 'برجاء ادخال كلمه رقم ',
            'furniture.required' =>'برجاء ادخال نعم او لا ',
            'type.required' => 'برجاء ادخال النوع شقه ام ستوديو ام غرقة نوم ام سرير ',
            'status.required' => 'برجاء ادخال حالة العقار ماجر ام لا',
            'area.required' => 'برجاء ادخال المساحه الخاصه بالاعلان',
            'area.numeric' => 'برجاء ادخال رقم ',
            'address.required' => 'برجاء ادخال عنوان الاعلان',
            'Latitude.required' => 'برجاء ادخال خطالطول',
            'Latitude.numeric' => 'برجاء ادخال رقم لخط الطول',
            'Longitude.required' => 'برجاء ادخال خط العرض ',
            'Longitude.numeric' => 'برجاء ادخال رقم لخط العرض',




        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
    /////////////store in databasee////////////////////////////////
    $user = Auth::user();
    $id = Auth::id();

    $advertisement = $user->advertisement()->create([
       "title" =>$request->title,
        "description" => $request->description,
       "bedroom_num" => $request->bedroom_num,
        "bathroom_num" => $request->bathroom_num,
        "beds_num"=> $request->beds_num,
        "level" => $request->level,
        "furniture "=> $request->furniture,
        "type" => $request->type,
        "area"=> $request->area,
        "address" => $request->address,
        "Latitude" => $request->Latitude,
        "Longitude" => $request->Longitude,
        "price"=>$request->price
    ]);
    
    $add_advertisement_data=[
        "advertisement" => $advertisement,
        "message"=>" تم اضافة اعلان من قبل المالك " . Auth::user()->name ."في انتظار موافقتك",
        "time" => carbon::now()
    ];
    event(new AddAdvertisement($add_advertisement_data));
    /////////store in table notification///////////
    $notification = New Notification ;
    $notification->user_id = 5;    ///////////ADMIN ID///////////
    $notification->advertisement_id = $advertisement->id;
    $notification->content = $add_advertisement_data["message"];
    $notification->status = "not_red";
    $notification->sent_at =$add_advertisement_data["time"];
    $notification->save();

    return response()->json( $advertisement);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function not_rented()
    {
        $user = Auth::user();
        $not_rented =$user->advertisement()->whereStatus("not rented")->get();
        return  response()->json([$not_rented,  $user]);
    }

    public function rented()
    {
        $user = Auth::user();
        $rented =$user->advertisement()->whereStatus("rented")->get();

        return  response()->json([$rented, $user ]);
    }

    public function pending()
    {
        $user = Auth::user();
        $pending =$user->advertisement()->whereControl("pending")->get();

        return  response()->json([$pending, $user ]);
    }
    public function accepted()
    {
        $user = Auth::user();
        $accepted =$user->advertisement()->whereControl("accepted")->get();

        return  response()->json([$accepted, $user ]);
    }
    public function declined()
    {
        $user = Auth::user();
        $declined =$user->advertisement()->whereControl("declined")->get();

        return  response()->json([$declined, $user ]);
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
      
    
    /////////////////////////////UPDATE/////////////////////////////////////////////

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        

        $validator =Validator::make($request->all(),[
            'title' => 'required|string|min:10|unique:advertisements|max:200',
            'description' => 'required|string|min:20|max:300',
            'price'=>'required|numeric',
            'bedroom_num'=>'required|numeric',
            'bathroom_num'=>'required|numeric',
            	
            'beds_num'=>'required|numeric',
            'level'=>'required|numeric',
            'type'=>'required',
            'status' => "required",
            'area'=>['required','numeric','regex:/^([1-9][0-9]{0,2}|1000)$/'],
            'furniture'=>"required",
            'address' => 'required|max:40|min:10|string',
            'Latitude'=>'required|numeric',
            'Longitude' =>'required|numeric',
        ],[
            'title.required' =>'برجاء ادخال عنوان الاعلان',
            'title.unique' => 'يجب ان يكون اسم الاعلان مميز و غير مكرر يرجاء اخال عنوان اخر ',
            'title.min' => 'برجاء ادخال عنوان لا تقل حروفه عن 20 حرف ',
            'title.max' => 'اقصى حد للعنوان هو 200 حرف',


            "description.required" => "وصف الاعلان مطلوب",
            "description.min" => "برجاء ادخال عدد حروف لا تقل عن 20 حرف لوصف الاعلان",
            "description.max" => "اقصي حد للحروف المطلو ادخالها هو 300 حرف",


            'price.required' => "برجاء ادخال السعر ",
            'price.numeric' => 'برجاء ادخال رقم ',

            'bedroom_num.required' => 'برجاء ادخال عدد غرف النوم ',
            'bedroom_num.numeric' => 'برجاء ادخال رقم ',
            'bathroom_num.required' => 'برجاء ادخال عدد غرف الحمام ',
            'bathroom_num.numeric' => 'برجاء ادخال رقم ',
            'bedroom_num.required' => 'برجاء ادخال عدد غرف النوم الخاصه بالاعلان',
            'bedroom_num.numeric' => 'برجاء ادخال رقم',
            'beds_num.required' => 'برجاء ادخال عدد الاسره الموجوده بالاعلان',
            'beds_num.numeric' => 'برجاء ادخال رقم ',
            'level.required' => 'برجاء ادخال رقم الدور',
            'level.numeric' => 'برجاء ادخال كلمه رقم ',
            'furniture.required' =>'برجاء ادخال نعم او لا ',
            'type.required' => 'برجاء ادخال النوع شقه ام ستوديو ام غرقة نوم ام سرير ',
            'status.required' => 'برجاء ادخال حالة العقار ماجر ام لا',
            'area.required' => 'برجاء ادخال المساحه الخاصه بالاعلان',
            'area.numeric' => 'برجاء ادخال رقم ',
            'address.required' => 'برجاء ادخال عنوان الاعلان',
            'Latitude.required' => 'برجاء ادخال خطالطول',
            'Latitude.numeric' => 'برجاء ادخال رقم لخط الطول',
            'Longitude.required' => 'برجاء ادخال خط العرض ',
            'Longitude.numeric' => 'برجاء ادخال رقم لخط العرض',




        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
    /////////////store in databasee////////////////////////////////
    $user = Auth::user();
   
        $advertisement= Advertisement::find($id);
        $advertisement->update([
            "title" =>$request->title,
        "description" => $request->description,
       "bedroom_num" => $request->bedroom_num,
        "bathroom_num" => $request->bathroom_num,
        "beds_num"=> $request->beds_num,
        "level" => $request->level,
        "furniture"=> $request->furniture,
        "type" => $request->type,
        "area"=> $request->area,
        "address" => $request->address,
        "Latitude" => $request->Latitude,
        "Longitude" => $request->Longitude,
        "control" => "pending",
        "price"=>$request->price]);
        //update
       return response()->json( [$advertisement , "user"=>Auth::user()]);
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
