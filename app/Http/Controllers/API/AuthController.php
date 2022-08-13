<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','verifyUser']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'برجاء ادخال البريد الإلكتروني ',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            "email.exists" => "البريد الالكتروني غير موجود",
            'password.required' => 'برجاء ادخال كلمه المرور الخاصه بك',
            "password.min" => "كلمة المرور التي تم ادخالها خطأ",

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],401);
        }
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
//        dd('d');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح لك بالدخول',
            ], 401);
        }
        if ($request->user()->status =='is_active') {
//
            if ($token && \auth()->user()->email_verified_at != null) {
                $user = Auth::user();
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }else{
                $user=User::where('email',$request->email)->get();
                return Response()->json(['message' => 'برجاء تفعيل بريدك الالكتروني ','user'=>$user]);

            }
        } else {
            return Response()->json(['message' => 'هذا البريد الالكتروني تم حظره من قبل الاداره']);
        }
    }

//
    public function register(Request $request){
            //validation
        $validator =Validator::make($request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'type'=>'required',
                'phone'=>'required|min:11|numeric|unique:users|regex:/^01[0125][0-9]{8}$/',
                'image'=>'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
                'gender'=>'required',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            ], [
                'name.required' => 'برجاء ادخال اسم المستخدم',
                'name.string' => 'لابد ان يكون اسم المستخدم بالحروف',
                'email.required' => 'برجاء ادخال البريد الإلكتروني ',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
                'type.required'=>'هذا الحقل مطلوب ادخاله',
                'password.required' => 'برجاء ادخال كلمه المرور',
                'password.min' => 'لابد ان تكون كلمه المرور اكثر من 8',
                'password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، ورمزا واحد',
                'password.confirmed' => ' برجاء تأكيد كلمه المرور التي تم ادخالها',
                'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
                'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
                'phone.unique'=>'رقم الهاتف مسجل بالفعل',
                'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',
                'image.required'=>'هذا الحقل مطلوب ادخاله',
                'image.mime'=>'صيغه الصوره غير مدعومه',
                'gender.required'=>'هذا الحقل مطلوب ادخاله'
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        if ($request->image != null) {
            $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();

        }else{
            $imageURL=null;
        }
           //name and email variable
        $name=$request->name;
        $email=$request->email;

            // store user in database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' =>$request->type,
            'phone' =>$request->phone,
            'gender' =>$request->gender,
            'image'=>$imageURL,
            'password' => Hash::make($request->password),
        ]);

        if ($user->image==null && $user->gender== 'female'){
            $user->image='https://bootstrapious.com/i/snippets/sn-about/avatar-4.png';
            $user->save();
        }elseif ($user->image==null && $user->gender== 'male'){
            $user->image='https://www.bootdey.com/img/Content/avatar/avatar7.png';
            $user->save();
        }
        $data=User::where('email',$user->email)->first();

            //mail verification
        $verification_code =random_int(100000, 999999);//Generate verification code
        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);
        $subject = "Please verify your email address.";
        Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
            function($mail) use ($email, $name, $subject){
                $mail->from('hello@example.com', "From for rent");
                $mail->to($email, $name);
                $mail->subject($subject);
            });


        return response()->json([
            'status' => 'success',
            'message' =>'نشكرك على اشتراكك معانا,برجاء متابعه بريدك لالكتروني لقد ارسلنا لك رمز التفعيل',
            'user' => $data,
        ]);
    }


    public function verifyUser(Request $request)
    {
        $verification_code=$request->all();
//        dd($verification_code);
        $check = DB::table('user_verifications')->where([['token',$request->token],['user_id',$request->id]])->first();

        if (!$check){
            return response()->json([
                    'success'=> false,
                    'message'=> 'الكود التي تم ادخاله خاطئ برجاء اعاده المحاوله'
                ]);
        }
        $user = User::find($check->user_id);
        $token = Auth::login($user);
        if(!is_null($check)){
            if($user->email_verified_at != null){
                return response()->json([
                    'success'=> true,
                    'message'=> 'هذا الحساب تم تفعيله من قبل',
                    'user'=>$user
                ]);
            }
//

            $user->email_verified_at=Carbon::now();
            $user->save();

            DB::table('user_verifications')->where([['token',$request->token],['user_id',$request->id]])->delete();

            return ([
                'success'=> true,
                'message'=> 'لقد تم تفعيل حسابك بنجاح',
                'user'=>$user,
                'token'=>$token
            ]);
        }


    }

    public function logout()
    {
//        dd(Auth::user());
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
