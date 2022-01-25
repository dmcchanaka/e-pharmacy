<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrnController extends Controller
{
    public function index(){
        return view('grn.index');
    }
}
