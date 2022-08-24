<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Advertisement_image;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;



class OwnerprofilesettinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user_info = Auth::user();
  return response()->json($user_info);
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
    public function update(Request $request)
    {
        $validator =Validator::make($request->except('password', 'new_password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',

            'phone'=>'required|min:11|numeric|regex:/^01[0125][0-9]{8}$/',
            'image'=>'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
            'gender'=>'required',



        ],[
            'name.required' => 'برجاء ادخال اسم المستخدم',
            'name.string' => 'لابد ان يكون اسم المستخدم بطريقه صحيحه',
            'email.required' => 'برجاء ادخال البريد الإلكتروني ',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
            'type.required'=>'هذا الحقل مطلوب ادخاله',
                      'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
            'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
            'phone.unique'=>'رقم الهاتف مسجل بالفعل',
            'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',
            'image.required'=>'هذا الحقل مطلوب ادخاله',
            'image.mime'=>'صيغه الصوره غير مدعومه',
            'gender.required'=>'هذا الحقل مطلوب ادخاله',

            'password.required' => 'برجاء ادخال كلمه المرور',

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }


     $filename = Auth::user()->image;

     $user= Auth::user();
     $message ="لم يتم تحديث كلمة المرور";
     if($request->new_password){

        //validation
        $validator =Validator::make($request->only('password', 'new_password'), [
             'password' => 'required',

            'new_password' => 'string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'

        ],['new_password.required' => 'برجاء ادخال كلمه المرور',
        'new_password.min' => 'لابد ان تكون كلمه المرور اكثر من 8',
        'new_password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، وحرف خاص واحد',
    ]);


     if (Auth::attempt(['password' => $request->password , "email" => $user->email]) && $request->new_password) {
        $user->fill([
         'password' => Hash::make($request->new_password)
         ])->save();
         $message =" تم تحديث كلمة المرور بنجاح";


        }elseif( Hash::check($request->new_password, $user->password)){
            $message = "كلمة المرور التي ادخلتها هي كلمة المرور القديمه الخاصه بك من فضلك ادخل كلمة مرور جديده ";

        }
        else{

                $message =" تم حفظ كلمة المرور القديمه  كلمة المرور التي ادخلتها لا تطابق كلمة المرور الخاصه بك ";
        }
    }

        if($request->file('image')){

            $destinationPath = public_path('images/owner_profile_images');
                $Extension = $request->file('image')->getClientOriginalExtension();
                $filename=mt_rand(100000000,99999999999999). "." . $Extension;
                $request->file('image')->move($destinationPath, $filename);

        }


       $update_user=$user->update([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'phone'=>$request->phone,
        'image'=>$filename,
        'status'=> 'is_active',
        'type'=>'owner'



       ]);
       $message2 ="تم تحديث بياناتك بنجاح";

       return response()->json(["message"=>$update_user ,"password"=> $message, "yourdata"=>$message2]);
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
    // update number and name
    public function name_phone_setting(Request $request){
        $validator =Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone'=>'required|min:11|numeric|regex:/^01[0125][0-9]{8}$/',



        ],[
            'name.required' => 'برجاء ادخال اسم المستخدم',
            'name.string' => 'لابد ان يكون اسم المستخدم بطريقه صحيحه',
            'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
            'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
            'phone.unique'=>'رقم الهاتف مسجل بالفعل',
            'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            $user= Auth::user();
            $update_user=$user->update([
                'name' => $request->name,
                'phone'=>$request->phone,
            ]);
            if ($user->type == 'renter' || $user->type == 'admin' || $user->type == 'superAdmin'){
                $user->payment='no';
                $user->save();
                return response()->json(["success"=> $update_user,"name"=> $user->name,"payment"=>$user->payment,"phone"=>$user->phone, "message" => "تم تحديث الاسم و رقم التليفون بنجاح"]);
            }else {
                $user->payment = $request->payment;
                $user->save();
                return response()->json(["success"=> $update_user,"name"=> $user->name,"payment"=>$user->payment,"phone"=>$user->phone, "message" => "تم تحديث الاسم و رقم التليفون بنجاح"]);

            }






    }

    //update password
    public function update_password(Request $request){
        $user= Auth::user();

        $validator =Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',

       ],['new_password.required' => 'برجاء ادخال كلمه المرور',
       'new_password.min' => 'لابد ان تكون كلمه المرور اكثر من 8',

       'new_password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، وحرف خاص واحد',
       'new_password.confirmed' => ' برجاء تأكيد كلمه المرور التي تم ادخالها',

       ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
            }

            if (Auth::attempt(['password' => $request->old_password , "email" => $user->email]) && !(Hash::check($request->new_password, $user->password))) {
                $user->fill([
                 'password' => Hash::make($request->new_password)
                 ])->save();
                 $message =" تم تحديث كلمة المرور بنجاح";


                }elseif( Hash::check($request->new_password, $user->password)&& Auth::attempt(['password' => $request->old_password , "email" => $user->email])){
                    $message = "كلمة المرور التي ادخلتها هي كلمة المرور القديمه الخاصه بك من فضلك ادخل كلمة مرور جديده ";

                }
                else{

                        $message =" تم حفظ كلمة المرور القديمه  كلمة المرور التي ادخلتها لا تطابق كلمة المرور الخاصه بك ";
                }

                return response()->json(["message"=>$message ]);


    }
     //update image
     function update_image(Request $request, $id){
        $validator =Validator::make($request->all(), [
            'image'=>'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
            ],[
                'image.required'=>'هذا الحقل مطلوب ادخاله',
                 'image.mime'=>'صيغه الصوره غير مدعومه'
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 401);
                    }
                    $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();

                    $user= User::find($id);

                    $user->image=$imageURL;
                    $user->save();
                    return response()->json(['success'=>true,'message'=>'تم التعديل بنجاح','image'=>$user->image]);
                }
     }


