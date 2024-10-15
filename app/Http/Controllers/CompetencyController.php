<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompetencyController extends Controller
{
    public function index()
    {
        return view('competency.index');
    }
}
