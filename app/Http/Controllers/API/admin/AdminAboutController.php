<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $about=About::get();
        return response()->json($about);
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
        $validator=Validator::make($request->all(),[
            'title'=>'required|string',
            'description'=>'required|string',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
        ],[
            'title.required'=>'هذا الحقل مطلوب ادخاله',
            'title.string'=>'برجاء ادخال العنوان بالحروف',
            'description.required'=>'هذا الحقل مطلوب ادخاله',
            'description.string'=>'برجاء ادخال الوصف بالحروف',
            'image.required'=>'هذا الحقل مطلوب ادخاله',
            'image.mime'=>'صيغه الصوره غير مدعومه',

        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }
        $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();



        $about=new About();
        $about->title=$request->title;
        $about->description=$request->description;
        $about->image=$imageURL;
        $about->save();

        return response()->json(['message'=>'تمت اضافه المحتوى بنجاح','about'=>$about]);

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
        $about=About::find($id);
        return response()->json(['data'=>$about]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$about_id)
    {

        $validator=Validator::make($request->all(),[
            'title'=>'required|string',
            'description'=>'required|string',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
        ],[
            'title.required'=>'هذا الحقل مطلوب ادخاله',
            'title.string'=>'برجاء ادخال العنوان بالحروف',
            'description.required'=>'هذا الحقل مطلوب ادخاله',
            'description.string'=>'برجاء ادخال الوصف بالحروف',
            'image.required'=>'هذا الحقل مطلوب ادخاله',
            'image.mime'=>'صيغه الصوره غير مدعومه',

        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $about=About::find($about_id);
//        $photo=$request->file('image');
//        dd($request->all());

        $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();


        $about->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'image'=>$imageURL
        ]);

        return response()->json(['message'=>'تم التعديل بنجاح','data'=>$about]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about_id)
    {
        $about_id->delete();
        return response()->json(['message'=>'تم المسح بنجاح']);
    }
}
