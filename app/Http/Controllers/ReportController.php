<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LevelsExport;
use App\Exports\DivisionsExport;
use App\Exports\TargetsExport;
use App\Imports\LevelsImport;
use App\User;
use App\Level;
use App\project;
use App\Target;
use Excel;
use PDF;

class ReportController extends Controller
{
    public function projects()
    {
        return view('pages.reports.projects');
    }

    public function projectsLoad(Request $request)
    {
        $status = $request->get('statusproject');
        $client = $request->get('client_id');
        $projects = project::with('project_has_client', 'po_has_projects')->orderBy('name', 'asc')->get();
        if ($status !== null) {
            $projects = project::with('project_has_client', 'po_has_projects')->where("statusproject", $status)->get();
            if ($client !== null) {
                $projects = project::with('project_has_client', 'po_has_projects')->where('client_id', $client)->where("statusproject", $status)->orderBy('name', 'asc')->get();
            }
        }
        return view('pages.reports.projectsLoad', compact('projects'));
    }

    public function projectsPdf(Request $request)
    {
        $status = $request->get('statusproject');
        $client = $request->get('client_id');
        $projects = project::with('project_has_client', 'po_has_projects')->orderBy('name', 'asc')->get();
        if ($status !== null) {
            $projects = project::with('project_has_client', 'po_has_projects')->where("statusproject", $status)->get();
            if ($client !== null) {
                $projects = project::with('project_has_client', 'po_has_projects')->where('client_id', $client)->where("statusproject", $status)->orderBy('name', 'asc')->get();
            }
        }
        $pdf = PDF::loadView('pages.reports.pdf.projects', compact('projects'));
        return $pdf->stream();
    }

    public function levelsExcel()
    {
        return view('pages.excelReport', [
            'route' => 'reports.levels.import.excel',
            'method' => 'POST',
            'url_export' => route('reports.levels.export.excel')
        ]);
    }

    public function divisionsExcel()
    {
        return view('pages.excelReport', [
            'route' => null,
            'method' => 'POST',
            'url_export' => route('reports.divisions.export.excel')
        ]);
    }

    public function targetsExcel()
    {
        return view('pages.excelReport', [
            'route' => null,
            'method' => 'POST',
            'url_export' => route('reports.targets.export.excel')
        ]);
    }

    public function levelsExcelExport()
    {
        return Excel::download(new LevelsExport, 'level_master.xlsx');
    }

    public function divisionsExcelExport()
    {
        return Excel::download(new DivisionsExport, 'division_master.xlsx');
    }

    public function targetsExcelExport($id)
    {
        $project = project::with('target_has_projects')->findOrFail($id);
        return Excel::download(new TargetsExport($project), 'target_all.xlsx');
    }

    public function levelsExcelImport(Request $request)
    {
        $this->validate($request, [
            'import' => 'required|mimes:csv,xls,xlsx'
        ]);

        if ($request->hasFile('import')) {
            $fileNameWithExt = $request->file('import')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExt = $request->file('import')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName) . '-' . time() . '.' . $fileExt;
            $path = $request->file('import')->move('files/excel/levels', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }

        Excel::import(new LevelsImport, public_path('files/excel/levels/' . $fileNameToStore));

        return response()->json([
            'message' => 'Level Imported'
        ], 200);
    }

    public function targets()
    {
        return view('pages.reports.targets');
    }

    public function targetsLoad(Request $request)
    {
        $project_id = $request->get('project_id');
        $sprint_id = $request->get('sprint_id');
        $targets = Target::with('target_has_projects', 'target_has_sprints')->orderBy('name', 'asc')->get();
        if ($project_id !== null) {
            $targets = Target::with('target_has_projects', 'target_has_sprints')->where("project_id", $project_id)->get();
            if ($sprint_id !== null) {
                $targets = Target::with('target_has_projects', 'target_has_sprints')->where('sprint_id', $sprint_id)->where("project_id", $project_id)->orderBy('name', 'asc')->get();
            }
        }
        return view('pages.reports.targetsLoad', compact('targets'));
    }
}