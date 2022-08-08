<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourit;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertisement;
use App\Models\Rating;





class HomeController extends Controller
{
    public function index(){

//         $alladvertisements= Advertisement::all();
// $rates=[];
//         foreach($alladvertisements as $oneadvertisement){
            
//             $oneadvertisement::with("");
            
//         }
        
                // return response()->json(["advertisement"=>$alladvertisements, "avarage" => $avg , "review_count" => $review_count]);


        
    


        if(Auth::user()){
        
        $advertisements = Advertisement::with('favourit')->get();
        
        return response()->json( ["favourite asvertisement"=>$advertisements]);

    }else{
        return response()->json( ["all asvertisement"=>Advertisement::all()]);

    }

   

        

}
}
