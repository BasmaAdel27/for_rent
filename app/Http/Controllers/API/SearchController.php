<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search( Request $request){
        
        if((($request->city_id)&&($request->type)&&($request->bedroom_num)) ){
        $search_results = DB::table("advertisements")->where([
            ['city_id', '=', $request->city_id],
            ['type', '=', $request->type],
            ["bedroom_num","=",$request->bedroom_num],
        ])->get();
        return response()->json($search_results);


        }elseif(($request->city_id)&&($request->type)){  

            $search_results = DB::table("advertisements")->where([
                ['city_id', '=', $request->city_id],
                ['type', '=', $request->type],
                
            ])->get();   
              return response()->json($search_results);
        }elseif(($request->city_id)&&($request->bedroom_num)){  

            $search_results = DB::table("advertisements")->where([
                ['city_id', '=', $request->city_id],
                ["bedroom_num","=",$request->bedroom_num]
                
            ])->get();   
              return response()->json($search_results);
        }elseif(($request->type)&&($request->bedroom_num)){  

            $search_results = DB::table("advertisements")->where([
                ['type', '=', $request->type],
                ["bedroom_num","=",$request->bedroom_num]
                
            ])->get();   
              return response()->json($search_results);
        }elseif(($request->type)){  

            $search_results = DB::table("advertisements")->where([
                ['type', '=', $request->type],
              
                
            ])->get();   
              return response()->json($search_results);
        }elseif(($request->bedroom_num)){  

            $search_results = DB::table("advertisements")->where([
          
                ["bedroom_num","=",$request->bedroom_num]
                
            ])->get();   
              return response()->json($search_results);
        }elseif(($request->city_id)){  

            $search_results = DB::table("advertisements")->where([
                ['city_id', '=', $request->city_id],
                
                
            ])->get();   
              return response()->json($search_results);
        }else{
            return response()->json(["message"=>"من فضلك ادخل كلمات البحث "]);
        }



    }
}
