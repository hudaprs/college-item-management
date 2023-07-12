<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\project;
use App\Po;

class ChartsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function backlog()
    {
        $projects = project::all();
        $pos = Po::orderBy('name', 'asc')->get();
        return view('pages.charts.backlog', compact('projects', 'pos'));
    }

    public function bug()
    {
        return view('pages.charts.bugs');
    }
}