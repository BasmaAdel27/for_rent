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

class Advertisement_imageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
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
    public function store(Request $request ){

       

        //image validation
        $validator =Validator::make($request->all(),[
        'image_name' => 'required|array|nullable',
        'image_name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        "advertisement_id" => "required",
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
                $destinationPath = 'public/images/';
                $Extension = $image->getClientOriginalExtension();
                $filename=mt_rand(100000000,99999999999999). "." . $Extension;
                $image->move($destinationPath, $filename);
               
                $ad_image []= Advertisement_image::create([
                    "image_name" =>  $filename,
                    "advertisement_id"=> $request->advertisement_id
                ]);
                
                // new Advertisement_image([
                //     "image_name" =>  $filename,
                //     "advertisement_id"=> $request->advertisement_id
                // ]);

            }
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


        // $validator =Validator::make($request->all(),[
        //     'image_name' => 'required|array|nullable',
        //     'image_name.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        //     "advertisement_id" => "required",
        //     ],[
        //         "image_name.required" => "بجب ان تدخل صوره الاعلان هذا الحقل مطلوب ",
        //         "image_name.array" => "يجب ان تكن مصفوفه صور او عدة صور للاعلان المطلوب ",
        //         "image_name.mimes" => "يجب اتكونالصوره من نوع jpg او jpegاو pngاو svgاو gif"
        //     ]);
        //     if ($validator->fails()) {
        //         return response()->json(['error'=>$validator->errors()], 401);
        //     }
        //     ///////////////image stores///////////////////////
        //     $images = DB::table('advertisements')
        //     ->join('advertisements_images', 'advertisements.id', '=', 'advertisements_images.advertisement_id')
            
        //     ->select('advertisements_images.*')
        //     ->get();
           
           
        //     $advertisement = new Advertisement;
        //     if($request->hasFile('image_name'))
        //     {
        //         $ad_image = [];
        //         foreach($request->file('image_name') as $image)
        //         {
        //             $destinationPath = 'public/images/';
        //             $Extension = $image->getClientOriginalExtension();
        //             $filename=mt_rand(100000000,99999999999999). "." . $Extension;
        //             $image->move($destinationPath, $filename);
                   
        //             $ad_image []= $images->toQuery()->update(array(
        //                 "image_name" =>  $filename,
        //                 "advertisement_id"=> $request->advertisement_id
        //                 )
        //             );
                    
        //             // new Advertisement_image([
        //             //     "image_name" =>  $filename,
        //             //     "advertisement_id"=> $request->advertisement_id
        //             // ]);
    
        //         }
        //         $advertisement->advertisement_image()->saveMany($ad_image);
        //         return response()->json("success", 200);
    
        // }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $images = DB::table('advertisements')
        //     ->join('advertisements_images', 'advertisements.id', '=', 'advertisements_images.advertisement_id')
            
        //     ->select('advertisements_images.*')
        //     ->delete();
          $advertisements= Advertisement::find($id);
          
          $advertisements->advertisement_image()-delete();

    }
}
