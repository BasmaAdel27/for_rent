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

            $ad=Advertisement::where([["status", "not rented"],["control", "accepted"]])->withCount('ratings')->withAvg("ratings", "count")->with('favourit',"advertisement_image")->get();
            return response()->json(["allAdvertisements" => $ad]);
        }else{


        $ad=Advertisement::where([["status", "not rented"],["control", "accepted"]])->withCount('ratings')->withAvg("ratings", "count")->with("advertisement_image")->get();

          return response()->json(["allAdvertisements" => $ad]);
        }




}
}
