<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
    }
    public function getResetToken(Request $request){
        $user = User::where('email', $request->email)->first();

            if(!$request->email)
                return response()->json(['message'=> 'برجاء ادخال البريد الالكتروني'], 400);
            if (!$user)
                return response()->json(['message'=> 'هذا البريد الالكتروني غير موجود'], 400);
            $token = PasswordReset::where('email', $request->email)->first();
            $digits=6;
            if(!$token) {
                $reset_password = new PasswordReset();
                $reset_password->email =$request->email;
                $reset_password->token =random_int(100000, 999999);
                $reset_password->save();

                $data = [
                    'name' => $user->username,
                    'subject' => 'this is token to reset password',
                    'token' => $reset_password->token
                ];
            }else{
                $token->update([
                    'token' =>random_int(100000, 999999)
                ]);
                $name=$user->name;
                $data = [
                    'name' => $name,
                    'subject' => 'this is token to reset password',
                    'token' => $token->token
                ];
            }

        Mail::send('email.reset', $data, function($mail) use ($user){
                $mail->from('hello@example.com', "From for rent");
                $mail->to($user->email);
                $mail->subject('اعادة تعيين كلمه المرور الخاصه بك');
            });
            return response()->json(['message' => 'تم ارسال الرمز برجاء التحقق من بريدك الالكتروني.']);

        }

    public function verify(Request $request){
        if (!$request->email)
            return response()->json(['message' => 'البريد الإلكتروني مطلوب'], 400);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['success'=>false,'message'=> 'البريد الإلكتروني غير صحيح'], 400);
        if (!$request->code)
            return response()->json(['success'=>false,'message' => 'الرمز مطلوب'], 400);


        $reset_password = PasswordReset::where('email', $request->email)->where('token', $request->code)->first();
//dd($reset_password);
        if ($reset_password == NULL || $user == null) {
            return response()->json(['success'=>false,'message'=>' الرمز خاطئ برجاء اعاده المحاوله'], 400);
        }else{
            $user=User::where('email',$request->email)->first();
            return response()->json(['success'=>true,'message'=>' الرمز التي تم ادخاله صحيح','data'=>$user,'code'=>$reset_password->token]);

        }
    }


    public function reset(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$request->email)
            return response()->json(['success'=>false,'message' => 'البريد الإلكتروني مطلوب'], 400);

        if (!$user)
            return response()->json(['success'=>false,'message'=> 'البريد الإلكتروني غير صحيح'], 400);
        if (!$request->code)
            return response()->json(['success'=>false,'message' => 'الرمز مطلوب'], 400);
        if (!$request->password)
            return response()->json(['success'=>false,'message' => 'كلمة المرور الجديدة مطلوبة'], 400);


        $reset_password = PasswordReset::where('email', $request->email)->where('token', $request->code)->first();
//dd($reset_password);
        if ($reset_password == NULL || $user == null) {
            return response()->json(['success'=>false,'message'=>' الرمز خاطئ برجاء اعاده المحاوله'], 400);
        } else {
            $validator=Validator::make($request->all(),[
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            ],[
                'password.required' => 'برجاء ادخال كلمه المرور',
                'password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، ورمزا واحد',
                'password.confirmed' => ' برجاء تأكيد كلمه المرور التي تم ادخالها',
            ]);
            if ($validator->fails()){
                return response()->json(['error'=>$validator->errors()],401);
            }
            $user->password = Hash::make($request->password);
            $user->save();
//            dd($reset_password->email);
            PasswordReset::find($reset_password->id)->delete();
            return response()->json(['success'=>true,'message' => 'تم تحديث كلمة المرور بنجاح','data'=>$user], 200);
        }

    }


}
