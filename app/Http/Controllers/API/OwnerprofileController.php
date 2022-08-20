<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Advertisement;


class OwnerprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

       $pending =$user->advertisement()->whereControl("pending")->get();
       $declined =$user->advertisement()->whereControl("declined")->get();

    $accepted_not_rented = $user->advertisement()->where('control', 'accepted')->get();
    $accepted_not_rented =  $accepted_not_rented->where('status', 'not rented');
 
    

return response()->json(["accepted-not-rented"=>$accepted_not_rented ,"pending"=>$pending , "declined"=> $declined] ,200);



    }
    //owner profile for public 
    public function owner_profile_for_public($id)
    {
        $user = User::find($id);


    $accepted_not_rented = $user->advertisement()->where([['control', 'accepted'], ['status', 'not rented']])->get();
    $accepted_rented =  $user->advertisement()->where([['control', 'accepted'], ['status', 'rented']]);
 
    

return response()->json(["accepted-not-rented"=>$accepted_not_rented ,"accepted-rented"=>$accepted_rented] ,200);


// return response()->json([$user] ,200);

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
        //
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
    public function destroy($id)
    {
        //
    }
}
