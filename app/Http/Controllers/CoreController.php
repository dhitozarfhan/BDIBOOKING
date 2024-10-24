<?php

namespace App\Http\Controllers;

use App\Models\Core;
use Illuminate\Http\Request;

class CoreController extends Controller
{
    public function index()
    {
        $cores = Core::all();
        return view('information.home', compact('cores'));
    }
}
