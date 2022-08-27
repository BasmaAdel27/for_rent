<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Advertisement;
use App\Models\City;


use Illuminate\Support\Facades\Auth;


class SearchController extends Controller
{
    public function search( Request $request){

     
        
        if((($request->city_id)&&($request->type)&&($request->bedroom_num)) ){
        $search_results = Advertisement::where([
            ['city_id', '=', $request->city_id],
            ['type', '=', $request->type],
            ["bedroom_num","=",$request->bedroom_num],
            ["status", "not rented"],["control", "accepted"]
        ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();
        return response()->json($search_results);


        }elseif(($request->city_id)&&($request->type)){  

            $search_results =  Advertisement::where([
                ['city_id', '=', $request->city_id],
                ['type', '=', $request->type],
                ["status", "not rented"],["control", "accepted"]
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();  
              return response()->json($search_results);
        }elseif(($request->city_id)&&($request->bedroom_num)){  

            $search_results =  Advertisement::where([
                ['city_id', '=', $request->city_id],
                ["bedroom_num","=",$request->bedroom_num],
                ["status", "not rented"],["control", "accepted"]
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get(); 
              return response()->json($search_results);
        }elseif(($request->type)&&($request->bedroom_num)){  

            $search_results =  Advertisement::where([
                ['type', '=', $request->type],
                ["bedroom_num","=",$request->bedroom_num],
                ["status", "not rented"],["control", "accepted"]
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();  
              return response()->json($search_results);
        }elseif(($request->type)){  

            $search_results = Advertisement::where([
                ['type', '=', $request->type],
                ["status", "not rented"],["control", "accepted"]
              
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();
              return response()->json($search_results);
        }elseif(($request->bedroom_num)){  

            $search_results =  Advertisement::where([
          
                ["bedroom_num","=",$request->bedroom_num],
                ["status", "not rented"],["control", "accepted"]
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();  
              return response()->json($search_results);
        }elseif(($request->city_id)){  

            $search_results = Advertisement::where([
                ['city_id', '=', $request->city_id],
                ["status", "not rented"],["control", "accepted"]

                
                
                
            ])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();   
              return response()->json($search_results);
        }else{
            $alladvertisement = Advertisement::where([["status", "not rented"],["control", "accepted"]])->get();
            return response()->json(["advertisements"=>$alladvertisement]);
        }

    

    }


public function city_choices(){
    $cities= DB::table('cities')
    ->join('advertisements', 'advertisements.city_id', '=', 'cities.id')
    ->select( [DB::raw('DISTINCT name') , "cities.id"])
    ->get();
  return response()->json(["city_choices" => $cities] );
}

public function type_choices(){
    $type=[];
    $search_results = Advertisement::where([ ["status", "not rented"],["control", "accepted"]])->get();
    foreach( $search_results as $adverisement){
    $type[]= $adverisement->type;
    }

  return response()->json(["type_choices" =>  array_unique($type)] );
}
public function bedroom_choices(){
    $bedroom=[];
    $search_results = Advertisement::where([ ["status", "not rented"],["control", "accepted"]])->get();
    foreach( $search_results as $adverisement){
    $bedroom[]= $adverisement->bedroom_num;
    }

  return response()->json(["bedroom_number_choices" =>  array_unique( $bedroom)] );
}
}