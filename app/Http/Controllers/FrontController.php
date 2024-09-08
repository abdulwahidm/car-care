<?php

namespace App\Http\Controllers;

use App\Models\CarService;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Serv;

class FrontController extends Controller
{
    //
    public function index() {
        $cities = City::all();
        $services = CarService::all();
        return view('front.index', compact('cities', 'services'));
    }
}               
