<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use App\Models\City;


use App\Models\Advertisement;
use App\Models\Advertisement_image;

use App\Events\AddAdvertisement;
use Illuminate\Support\Carbon;






class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','show']]);
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
    public function create(Request $request )
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
            'image_name' => 'required|array|nullable',
        'image_name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',

            "city_id" => "required"
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


            "city_id.required" => "برجاء ادخال اسم المدينه او المحافظه الحاصه بالاعلان",
            "image_name.required" => "بجب ان تدخل صوره الاعلان هذا الحقل مطلوب ",
            "image_name.array" => "يجب ان تكن مصفوفه صور او عدة صور للاعلان المطلوب ",
            "image_name.mime" => "يجب اتكونالصوره من نوع jpg او jpegاو pngاو svgاو gif"




        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
    //store in databasee
    $user = Auth::user();

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
        "price"=>$request->price,
        "city_id" => $request->city_id
    ]);

    //store image
    if($request->hasFile('image_name'))
    {
        $ad_image = [];
        foreach($request->file('image_name') as $image)
        {
            $imageURL = cloudinary()->upload($image->getRealPath())->getSecurePath();


            $ad_image []= Advertisement_image::create([
                "image_name" =>  $imageURL,
                "advertisement_id"=> $advertisement->id
            ]);



        }


        $advertisement->advertisement_image()->saveMany($ad_image);
    }
    //end store image
    $add_advertisement_data=[
        "advertisement" => $advertisement,
        "message"=>" تم اضافة اعلان من قبل المالك " . Auth::user()->name ."في انتظار موافقتك",
        "time" => carbon::now()
    ];
    event(new AddAdvertisement($add_advertisement_data));
    //store in table notification
    $notification = New Notification ;
    $notification->user_id = 1;    //ADMIN ID
    $notification->advertisement_id = $advertisement->id;
    $notification->content = $add_advertisement_data["message"];
    $notification->status = "not_red";
    $notification->sent_at =$add_advertisement_data["time"];
    $notification->save();

    return response()->json([ $advertisement ,"user"=>$user, "images" => $ad_image] );

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
        $not_rented =$user->advertisement()->whereStatus("not rented")->whereControl("accepted")->get();
        return  response()->json([$not_rented,  $user]);
    }

    public function rented()
    {
        $user = Auth::user();
        $rented =$user->advertisement()->whereStatus("rented")->whereControl("accepted")->get();

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
    public function show(Advertisement $advertisement_id)
    {
        $advertisement=Advertisement::where([['id',$advertisement_id->id],['status','not rented'],['control','accepted']])
            ->with('advertisement_image','user')->get();
        $rating=Rating::where('advertisement_id',$advertisement_id->id)->with('user')->get();
        $advs_owner=Advertisement::where([['status','not rented'],['control','accepted'],['user_id',$advertisement_id->user_id]])->get();
        $adv_suggestion=Advertisement::where('id','<>',$advertisement_id->id)->withAvg('ratings','count')->withCount('ratings')
            ->where([['city_id',$advertisement_id->city_id],['status','not rented'],['control','accepted'],['type',$advertisement_id->type]])->get();
        return response()->json(['advertisement'=>$advertisement,'reviews'=>$rating,'reviews_num'=>count($rating),'reviews_avg'=>$rating->avg('count'),'advertisement_num'=>count($advs_owner),'suggestion'=>$adv_suggestion]);
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
            'title' => 'required|string|min:10|max:200',
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
            'city_id'=>'required',
            'image_name' => 'required|array',
            'image_name.*' => 'image|mime:jpeg,png,jpg,gif,svg',

        ],[
            'title.required' =>'برجاء ادخال عنوان الاعلان',
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
            "city_id.required" => "برجاء ادخال اسم المدينه او المحافظه الحاصه بالاعلان",
            "image_name.required" => "بجب ان تدخل صوره الاعلان هذا الحقل مطلوب ",
                "image_name.array" => "يجب ان تكن مصفوفه صور او عدة صور للاعلان المطلوب ",
                "image_name.mimes" => "يجب اتكونالصوره من نوع jpg او jpegاو pngاو svgاو gif"







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
       
        "control" => "pending",
        "price"=>$request->price,
        "city_id" =>$request->city_id
    ]);
    //update image
    $advertisement = Advertisement::find($id);
    $advertisement->advertisement_image()->delete();
    $photos = $request->file('image_name');




        foreach ($photos as $photo) {

            $imageURL = cloudinary()->upload($photo->getRealPath())->getSecurePath();
            $adPhoto = new Advertisement_image;
            $adPhoto->advertisement_id = $advertisement->id;
           $ad_img[]= $adPhoto->image_name   = $imageURL;
            $adPhoto->save();


        }


    //end update image
        //update
       return response()->json( ["advertisement"=>$advertisement , "user"=>Auth::user(), "images" => $ad_img]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $advertisement = Advertisement::find($id);
        if((Auth::user()->type)=="owner"&& (($advertisement->user_id) == (Auth::user()->id)) ){
            $advertisement->delete();
            return response()->json("تم المسح بنجاح");


        }else{
            $message = "غير مصرح لك بالمسح ";
            return response()->json( [ "message" =>$message]);

        }
    }
}
