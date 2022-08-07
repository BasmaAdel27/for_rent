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
        $validator=Validator::make($request->all(),[
            'instagram'=>'nullable|string',
            'facebook'=>'nullable|string',
            'twitter'=>'nullable|string',
            'email'=>'nullable|string|email',
            'logo'=>'required|image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
            'phone'=>'nullable|min:11|numeric|regex:/^01[0125][0-9]{8}$/',
        ],[
            'email.email'=>'صيغه البريد الالكتروني غير صحيحه',
            'logo.required'=>'هذا الحقل مطلوب ادخاله',
            'logo.image'=>'هذا الحقل يجب ان يكون صوره',
            'logo.mime'=>'صيغه الصوره غير مدعومه',
            'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
            'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
            'phone.regex'=>'لابد ان يبدا هاتفك ب 015,012,011,010',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }

        $strToArr = explode(".", $_FILES["logo"]["name"]);
        $extension = end($strToArr);
        $newimagename = round(microtime(true)) . '.' . $extension;
        $request->logo->move(public_path('images'), $newimagename);

        $followUs=Follow_us::find($id);
        $followUs->update([
            'instagram'=>$request->instagram,
            'facebook'=>$request->facebook,
            'twitter'=>$request->twitter,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'logo'=>$newimagename,
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
