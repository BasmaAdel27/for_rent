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
        if(Auth::user()){
            
            $ad=Advertisement::where([["status", "not rented"],["control", "accepted"]])->withCount('ratings')->withAvg("ratings", "count")->with('favourit')->get();
            return response()->json(["all advertisements" => $ad]);
        }else{


        $ad=Advertisement::where([["status", "not rented"],["control", "accepted"]])->withCount('ratings')->withAvg("ratings", "count")->get();

          return response()->json(["all advertisements" => $ad]);
        }
    

        

}
}
