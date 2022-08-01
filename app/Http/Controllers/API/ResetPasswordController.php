<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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


}
