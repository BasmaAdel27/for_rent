<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact_us;
use App\Models\Contact_us_file;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminContactusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports=Contact_us::all();
        return response()->json(['reports'=>$reports]);
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
        if (!Auth::user()) {
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'phone' => 'required|min:11|numeric|regex:/^01[0125][0-9]{8}$/',
                    'description' => 'required|string',
                    'image' => 'array',
                    'image.*' => 'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
                ], [
                    'name.required' => 'برجاء ادخال اسم المستخدم',
                    'name.string' => 'لابد ان يكون اسم المستخدم بالحروف',
                    'email.required' => 'برجاء ادخال البريد الإلكتروني ',
                    'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                    'phone.min' => 'رقم الهاتف لابد ان يكون مكون من 11 رقم ',
                    'phone.required' => 'برجاء ادخال رقم الهاتف الخاص بك',
                    'phone.regex' => 'لابد ان يبدا هاتفك ب 015,012,011,010',
                    'image.mime' => 'صيغه الصوره غير مدعومه',
                    'description.required' => 'هذا الحقل مطلوب ادخاله'
                ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $report=Contact_us::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'description'=>$request->description,
                'url'=>$request->url,
                'phone'=>$request->phone,
            ]);

            if($request->hasFile('image')) {
                $images = [];
                foreach ($request->file('image') as $image) {
                    $imageURL = cloudinary()->upload($image->getRealPath())->getSecurePath();


                    $images[] = Contact_us_file::create([
                        "file_name" => $imageURL,
                        "contactus_id" => $report->id
                    ]);
                }
            }
            return response()->json(['message' => 'تمت اضافه الشكوى بنجاح']);
        }
        else{
            $validator = Validator::make($request->all(),
                [

                    'description' => 'required|string',
                    'image' => 'array',
                    'image.*' => 'image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
                    ], [
                    'image.mime' => 'صيغه الصوره غير مدعومه',
                    'description.required' => 'هذا الحقل مطلوب ادخاله'
                ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $report=Contact_us::create([
                'name'=>Auth::user()->name,
                'email'=>Auth::user()->email,
                'description'=>$request->description,
                'url'=>$request->url,
                'phone'=>Auth::user()->phone,
                'user_id'=>Auth::user()->id
            ]);
//dd($report->id);
            $id=$report->id;
            if($request->hasFile('image')) {
                $images = [];
                foreach ($request->file('image') as $image) {
                    $imageURL = cloudinary()->upload($image->getRealPath())->getSecurePath();


                    $images[] = Contact_us_file::create([
                        "file_name" => $imageURL,
                        "contactus_id" =>$id
                    ]);
                }
            }
            return response()->json(['message'=>'تمت اضافه الشكوى بنجاح']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact_us $contactus_id)
    {
        $report=Contact_us::where('id',$contactus_id->id)->with('contact_us_file')->get();
        return response()->json(['report'=>$report]);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($contactus_id)
    {
        $report=Contact_us::find($contactus_id)->delete();

        return response()->json(['message'=>'تم المسح بنجاح']);

    }
}
