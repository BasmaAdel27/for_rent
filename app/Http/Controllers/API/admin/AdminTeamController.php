<?php

namespace App\Http\Controllers\API\admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams=Team::all();
        return response()->json(['data'=>$teams]);
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
            'name'=>'required|string',
            'jobTitle'=>'required|string',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
        ],[
            'name.required'=>'هذا الحقل مطلوب ادخاله',
            'jobTitle.required'=>'هذا الحقل مطلوب ادخاله',
            'image.required'=>'هذا الحقل مطلوب ادخاله',
            'image.mime'=>'صيغه الصوره غير مدعومه',
            'image.image'=>'هذا الحقل لابد ان يكون صوره'
        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }
        $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();

        $team=Team::create([
            'name'=>$request->name,
            'jobTitle'=>$request->jobTitle,
            'image'=>$imageURL
        ]);
        return response()->json([
           'success'=>true,
            'message'=>'تمت الاضافه بنجاح',
            'data'=>$team,
        ]);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $team_id)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required|string',
            'jobTitle'=>'required|string',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,PNG,JPG,JPEG,svg|max:2048',
        ],[
            'name.required'=>'هذا الحقل مطلوب ادخاله',
            'jobTitle.required'=>'هذا الحقل مطلوب ادخاله',
            'image.required'=>'هذا الحقل مطلوب ادخاله',
            'image.mime'=>'صيغه الصوره غير مدعومه',
            'image.image'=>'هذا الحقل لابد ان يكون صوره'
        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }
        $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();

        $team=Team::find($team_id);
        $team->update([
            'name'=>$request->name,
            'jobTitle'=>$request->jobTitle,
            'image'=>$imageURL
        ]);
        return response()->json([
            'success'=>true,
            'message'=>'تم التعديل بنجاح',
            'data'=>$team,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($team_id)
    {
        $team=Team::find($team_id);
        $team->delete();
        return response()->json([
            'success'=>true,
            'message'=>'تم الحذف بنجاح',
        ]);
    }
}
