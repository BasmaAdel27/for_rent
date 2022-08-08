<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Advertisement_image;
use App\Models\City;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class Advertisement_imageController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request , $id){

       

        //image validation
        $validator =Validator::make($request->all(),[
        'image_name' => 'required|array|nullable',
        'image_name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[
            "image_name.required" => "بجب ان تدخل صوره الاعلان هذا الحقل مطلوب ",
            "image_name.array" => "يجب ان تكن مصفوفه صور او عدة صور للاعلان المطلوب ",
            "image_name.mimes" => "يجب اتكونالصوره من نوع jpg او jpegاو pngاو svgاو gif"
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        ///////////////image stores///////////////////////
        // $advertisement_image = new Advertisement_image;
        $advertisement = new Advertisement;
        if($request->hasFile('image_name'))
        {
            $ad_image = [];
            foreach($request->file('image_name') as $image)
            {
                $imageURL = cloudinary()->upload($image->getRealPath())->getSecurePath();

               
                $ad_image []= Advertisement_image::create([
                    "image_name" =>  $imageURL,
                    "advertisement_id"=>$id
                ]);
                
                

            }
            return response()->json("success");

            $advertisement->advertisement_image()->saveMany($ad_image);

 


        }
           
            
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         //image validation
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

        $validator =Validator::make($request->all(),[
            'image_name' => 'required|array',
            'image_name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ],[
                "image_name.required" => "بجب ان تدخل صوره الاعلان هذا الحقل مطلوب ",
                "image_name.array" => "يجب ان تكن مصفوفه صور او عدة صور للاعلان المطلوب ",
                "image_name.mimes" => "يجب اتكونالصوره من نوع jpg او jpegاو pngاو svgاو gif"
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
       
       


    $advertisement = Advertisement::find($id);
    $advertisement->advertisement_image()->delete();
    $photos = $request->file('image_name');




        foreach ($photos as $photo) {

            $imageURL = cloudinary()->upload($photo->getRealPath())->getSecurePath();
            $adPhoto = new Advertisement_image;
            $adPhoto->advertisement_id = $advertisement->id;
           $ad_img[]= $adPhoto->image_name   = $imageURL;
            $adPhoto->save();


        }
        return response()->json("updated successfully");

        
      
        
        
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement_id)
    {
       $id=  $advertisement_id->id; 

       $as=$advertisement_id->advertisement_image();
       return $as;

    }
}
