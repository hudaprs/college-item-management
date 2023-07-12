<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogtimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('pages.logtime.index');
    }

    public function indexByEmployees()
    {
        return view('pages.logtime.by_employees.index');
    }
}
