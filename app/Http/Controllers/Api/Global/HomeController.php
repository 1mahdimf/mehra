<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\HomeResource;
use App\Http\Resources\Api\HomeResourceCollection;
use App\Models\Award;
use App\Models\Category;
use App\Models\Home;
use App\Models\Product;

class HomeController extends Controller {

    public function index()
    {
        $home = Home::query()->get(['key','value','model','order','where','with'])->sortBy('order')->pluck('json')->flatMap(function ($v){
            return $v;
        });
//        if(!cache()->has('home')){
//            cache()->set('home',$home,5);
//        }
//        $home = cache()->get('home');
//        dd($home->toArray());
        return response()->json($home->toArray());
    }
}
