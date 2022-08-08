<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller


{
    public function store(){
        $data = [
            ['name' =>"القاهره"],
            ['name' =>"الجيزه"], 
            ['name' =>"الاسكندريه"], 
            ['name' =>"الدقهليه"], 
            ['name' =>"الشرقيه"], 
            ['name' =>"المنوفيه"], 
            ['name' =>"القلبوبيه"], 
            ['name' =>"البحيره"], 
            ['name' =>"الغربيه"], 
            ['name' =>"بورسعيد"], 
            ['name' =>"دمياط"], 
            ['name' =>"الاسماعليه"], 
            ['name' =>"السويس"], 
            ['name' =>"كفرالشيخ"], 
            ['name' =>"الفيوم"], 
            ['name' =>"بني سويف"], 
            ['name' => "مطروح"], 
            ['name' =>"شمال سيناء"], 
            ['name' =>"جنوب سيناء"], 
            ['name' =>"المنيا"], 
            ['name' =>"اسيوط"], 
            ['name' =>"سوهاج"], 
            ['name' =>"قنا"], 
            ['name' => "البحر الاحمر"],   
            ['name' =>"الاقصر"], 
            ['name' =>"اسوان"], 
            ['name' =>"الواحات"], 
            ['name' => "الوادي الجديد"],   
         ];
         
         City::insert($data);
         return response()->json("success");
    }
}
