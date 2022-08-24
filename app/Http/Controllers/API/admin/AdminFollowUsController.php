<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\Follow_us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminFollowUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links=Follow_us::first();
        return response()->json(['links'=>$links]);
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
        $links=Follow_us::find($id);
        return response()->json(['data'=>$links]);
    }

    public function updateImage(Request $request,$id){
        $validator=Validator::make($request->all(),[

            'logo'=>'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
        ],[

            'logo.mime'=>'صيغه الصوره غير مدعومه',
            'logo.image'=>'هذا الحقل لابد ان يكون صوره'
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }
        $image=Follow_us::find($id);
        $imageURL = cloudinary()->upload($request->file('logo')->getRealPath())->getSecurePath();

        $image->logo=$imageURL;
        $image->save();
        return response()->json(['success'=>true,'message'=>'تم التعديل بنجاح','logo'=>$image]);
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
        $validator=Validator::make($request->all(),[
            'instagram'=>'nullable|string',
            'facebook'=>'nullable|string',
            'twitter'=>'nullable|string',
            'email'=>'nullable|string|email',
            'phone'=>'nullable|min:11|numeric|regex:/^01[0125][0-9]{8}$/',
        ],[
            'email.email'=>'صيغه البريد الالكتروني غير صحيحه',

            'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
            'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
            'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }



        $followUs=Follow_us::find($id);
        $followUs->update([
            'instagram'=>$request->instagram,
            'facebook'=>$request->facebook,
            'twitter'=>$request->twitter,
            'email'=>$request->email,
            'phone'=>$request->phone,
        ]);

        return response()->json(['message'=>'تم التعديل بنجاح','data'=>$followUs]);
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
