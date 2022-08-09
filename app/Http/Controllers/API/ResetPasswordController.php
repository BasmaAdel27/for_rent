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

            if(!$request->email)
                return response()->json(['message'=> 'برجاء ادخال البريد الالكتروني'], 400);
            $user = User::where('email', $request->email)->first();
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
                $data = [
                    'name' => $user->name,
                    'subject' => 'this is token to reset password',
                    'token' => $token->token
                ];
            }

        Mail::send('email.reset', $data, function($mail) use ($user){
                $mail->from('hello@example.com', "From for rent");
                $mail->to($user->email);
                $mail->subject('Reset password for your account');
            });
            return response()->json(['message' => 'تم ارسال الرمز برجاء التحقق من بريدك الالكتروني.']);

        }




    public function reset(Request $request)
    {
        if (!$request->email)
            return response()->json(['message' => 'البريد الإلكتروني مطلوب'], 400);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['message'=> 'البريد الإلكتروني غير صحيح'], 400);
        if (!$request->code)
            return response()->json(['message' => 'الرمز مطلوب'], 400);
        if (!$request->password)
            return response()->json(['message' => 'كلمة المرور الجديدة مطلوبة'], 400);

        $user = User::where('email', $request->email)->first();

        $reset_password = PasswordReset::where('email', $request->email)->where('token', $request->code)->first();
//dd($reset_password);
        if ($reset_password == NULL || $user == null) {
            return response()->json(['message'=>' الرمز خاطئ برجاء اعاده المحاوله'], 400);
        } else {
            $validator=Validator::make($request->all(),[
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            ],[
                'password.required' => 'برجاء ادخال كلمه المرور',
                'password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، وحرف خاص واحد',
                'password.confirmed' => ' برجاء تأكيد كلمه المرور التي تم ادخالها',
            ]);
            if ($validator->fails()){
                return response()->json(['error'=>$validator->errors()],401);
            }
            $user->password = Hash::make($request->password);
            $user->save();

//            dd($reset_password->email);
            PasswordReset::destroy($reset_password->email);
            return response()->json(['message' => 'تم تحديث كلمة المرور بنجاح'], 200);
        }

    }


}
