<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\project;

class ArchiveController extends Controller
{
    public function projects()
    {
        return view('pages.archives.projectsArchive');
    }

    public function projectsLoad(Request $request)
    {
        $year = $request->input('year');
        if (!empty($year)) {
            $projects = project::where('statusproject', 1)->whereYear('updated_at', $year)->orderBy('name')->get();
        } else {
            $projects = project::where('statusproject', 1)->orderBy('name')->get();
        }
        return view('pages.archives.projectsArchiveLoad', compact('projects'));
    }
}