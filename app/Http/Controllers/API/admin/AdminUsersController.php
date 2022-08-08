<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUsersController extends Controller
{
    public function AllRenters(){
        $renters=User::where([['type','renter'],['status','is_active']])->get();
        return response()->json(['renters'=>$renters,'counts'=>count($renters)]);
    }

    public function AllOwners(){
        $owners=User::where([['type','owner'],['status','is_active']])->get();
        return response()->json(['owners'=>$owners,'counts'=>count($owners)]);
    }

    public function AllAdmins(){
        $admins=User::where([['type','admin'],['status','is_active']])->get();
        return response()->json(['renters'=>$admins,'counts'=>count($admins)]);
    }


    public function AllBlocks(){
        $blocks=User::where('status','is_blocked')->get();
        return response()->json(['blocks'=>$blocks,'counts'=>count($blocks)]);
    }

    public function destroy(User $userId){

        $user=$userId->delete();
        return response()->json(['message'=>'تم مسح المستخدم بنجاح']);
    }

    public function block(User $userId){

        $user=User::where('id',$userId->id)->first();
        if ($user->status == 'is_active') {
            $user->status = 'is_blocked';
            $user->save();
            return response()->json(['message' => 'تم حظر المستخدم بنجاح','user'=>$user]);
        }else{
            $user->status = 'is_active';
            $user->save();
            return response()->json(['message' => 'تم فك حظر المستخدم بنجاح','user'=>$user]);
        }
    }

    public function addAdmin(Request $request){
        $validator =Validator::make($request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
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
                'password.required' => 'برجاء ادخال كلمه المرور',
                'password.min' => 'لابد ان تكون كلمه المرور اكثر من 8',
                'password.regex'=>'يجب أن تتكون كلمة المرور الخاصة بك من أكثر من 8 أحرف ، ويجب أن تحتوي على الأقل على حرف كبير واحد ، وحرف صغير واحد ، ورقم واحد ، وحرف خاص واحد',
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

        // store admin in database
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' =>'admin',
            'phone' =>$request->phone,
            'gender' =>$request->gender,
            'image'=>$imageURL,
            'password' => Hash::make($request->password)
        ]);
        $admin->email_verified_at=Carbon::now();
        $admin->save();

        if ($admin->image==null && $admin->gender== 'female'){
            $admin->image='https://bootstrapious.com/i/snippets/sn-about/avatar-4.png';
            $admin->save();
        }elseif ($admin->image==null && $admin->gender== 'male'){
            $admin->image='https://www.bootdey.com/img/Content/avatar/avatar7.png';
            $admin->save();
        }

            return response()->json(['message'=>'تمت اضافه المدير بنجاح','admin'=>$admin]);
    }

}
