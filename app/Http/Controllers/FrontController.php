<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class FrontController extends Controller
{
    //
    public function index() {
        $cities = City::all();
        return view('front.index', compact('cities'));
    }
}               
