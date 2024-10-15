<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IbizaController extends Controller
{
    public function index()
    {
        return view('ibiza.index');
    }
}
