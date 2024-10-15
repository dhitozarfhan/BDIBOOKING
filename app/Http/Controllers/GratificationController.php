<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GratificationController extends Controller
{
    public function index()
    {
        return view('gratification.index');
    }
}
