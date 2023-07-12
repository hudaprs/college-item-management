<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Po;
use App\project;
use App\Target;
use App\Sprint;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }
}