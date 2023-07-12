<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TargetsImport;
use App\project;
use App\Sprint;
use App\Target;
use App\Po;
use DB;
use Carbon;
use Excel;

class LogbookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.logbooks.index');
    }

    public function addNewSprint(Request $request, $id)
    {
        $project = project::findOrFail($id);
        $project->save();
        $project->project_sprint()->sync($request->input('sprints'));

        if ($project) {
            return response()->json([
                'message' => 'Sprint Has Been Added',
                'data' => $project,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed adding sprint'
            ]);
        }
    }

    public function editproject(Request $request)
    {
        $sprints = Sprint::all();
        $project_id = $request->get('project_id');
        $po_id = $request->get('po_id');
        $year = $request->get('year');
        $targets = Target::where('project_id', $project_id)->whereYear('created_at', $year)->get();
        $project = project::findOrFail($project_id);
        $counts = DB::table('targets')
            ->join('projects', 'targets.project_id', '=', 'projects.id')
            ->join('pos', 'projects.po_id', '=', 'pos.id')
            ->where('projects.po_id', $po_id)
            ->where('projects.id', $project_id)
            ->whereYear('targets.created_at', $year)
            ->get();
        return view('pages.logbooks.editproject', compact('sprints', 'counts', 'targets', 'project'));

    }

    public function editTargetInproject(Request $request)
    {
        //new target
        $sprints = Sprint::all();
        $arrSprint = [];
        foreach ($sprints as $sprint) {
            $arrSprint[] = $sprint->id;
        }
        foreach ($arrSprint as $sprintId) {
            if (!empty($request->input('new_target_sprint_' . $sprintId))) {
                $targets = $request->input('new_target_sprint_' . $sprintId);
                $str = preg_split("/[`]/", $targets); //proses pemisahaan target
                $searchword = 'done';
                for ($i = 1; $i < count($str); $i++) {
                    $int = preg_replace('/[*]/', ' ', $str[$i]); //proses pemisahan status target
                    $nameTarget = preg_replace('/ done/', '', $int); //proses memisahkan nama target
                    $arr = [$int];
                    foreach ($arr as $k => $v) {
                        if ($nameTarget !== "") {
                            if (preg_match("/\b$searchword\b/i", $v)) { //proses pencarian status done dan penginputan
                                $target = new Target;
                                $target->project_id = $request->input('project_id');
                                $target->sprint_id = $sprintId;
                                $target->name = $nameTarget;
                                $target->status = "DONE";
                                $target->created_by = auth()->user()->id;
                                $target->save();
                            } else {
                                $target = new Target;
                                $target->project_id = $request->input('project_id');
                                $target->sprint_id = $sprintId;
                                $target->name = $nameTarget;
                                $target->created_by = auth()->user()->id;
                                $target->save();
                            }
                        }
                    }
                }
            }
        }

        //update target
        $arr = $request->id;
        foreach ($arr as $id) {
            $status_id = 'status_' . $id;
            $name_id = 'name_' . $id;
            $delete_id = 'delete_' . $id;
            $status = $request->$status_id;
            $name = $request->$name_id;
            $delete = $request->$delete_id;
            $target = Target::findOrFail($id);
            if (empty($delete)) {
                if (!empty($status)) {
                    $target->status = "DONE";
                    if ($name !== null) {
                        $target->name = $name;
                    }
                    $target->save();
                } else {
                    $target->status = NULL;
                    if ($name !== null) {
                        $target->name = $name;
                    }
                    $target->save();
                }
            } else {
                $target->delete();
            }
        }

        return response()->json([
            'message' => 'project Updated',
            'po_id' => $request->get('po_id'),
            'year' => $request->get('year')
        ], 200);
    }

    public function editStatusTargets(Request $request)
    {
        //update target
        $arr = $request->id;
        foreach ($arr as $id) {
            $status_id = 'status_' . $id;
            $delete_id = 'delete_' . $id;
            $status = $request->input('status_' . $id);
            $delete = $request->input('delete_' . $id);
            $target = Target::findOrFail($id);
            if (empty($delete)) {
                if (!empty($status)) {
                    $target->status = "DONE";
                    $target->save();
                } else {
                    $target->status = NULL;
                    $target->save();
                }
            } else {
                $target->delete();
            }
        }

        return response()->json([
            'message' => 'project Updated',
            'po_id' => $request->get('po_id'),
            'year' => $request->get('year')
        ], 200);
    }

    public function addSprintTarget(Request $request)
    {
        $project = project::findOrFail($request->get('project_id'));
        $year = $request->get('year');
        $sprints = Sprint::orderBy('name', 'asc')->get();
        return view('pages.logbooks.addTarget', compact('project', 'year', 'sprints'));
    }

    public function addSprintTargetproject(Request $request)
    {
        // Validate Form
        $this->validate($request, [
            'sprint' => 'required',
            'import' => 'mimes:csv,xls,xlsx'
        ]);

        $targets = $request->input('manyTarget');
        $str = preg_split("/[`]/", $targets); //proses pemisahaan target
        $searchword = 'done';
        for ($i = 1; $i < count($str); $i++) {
            $int = preg_replace('/[*]/', ' ', $str[$i]); //proses pemisahan status target
            $nameTarget = preg_replace('/ done/', '', $int); //proses memisahkan nama target
            $arr = [$int];
            foreach ($arr as $k => $v) {
                if ($nameTarget !== "") {
                    if (preg_match("/\b$searchword\b/i", $v)) { //proses pencarian status done dan penginputan
                        $target = new Target;
                        $target->project_id = $request->input('project');
                        $target->sprint_id = $request->input('sprint');
                        $target->po_id = $request->input('po_id');
                        $target->name = $nameTarget;
                        $target->status = "DONE";
                        $target->created_by = auth()->user()->id;
                        $target->created_at = date($request->get('year') . '-m-d H:i:s');
                        $target->save();
                    } else {
                        $target = new Target;
                        $target->project_id = $request->input('project');
                        $target->sprint_id = $request->input('sprint');
                        $target->po_id = $request->input('po_id');
                        $target->name = $nameTarget;
                        $target->created_by = auth()->user()->id;
                        $target->created_at = date($request->get('year') . '-m-d H:i:s');
                        $target->save();
                    }
                }
            }
        }

        if ($request->hasFile('import')) {
            $fileNameWithExt = $request->file('import')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExt = $request->file('import')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName) . '-' . time() . '.' . $fileExt;
            $path = $request->file('import')->move('files/excel/targets/', $fileNameToStore);
            Excel::import(new TargetsImport($request->input('project'), $request->input('sprint'), auth()->user()->id), public_path('files/excel/targets/' . $fileNameToStore));
        } else {
            $fileNameToStore = null;
        }

        return response()->json([
            'message' => 'project Updated',
            'po_id' => $request->get('po_id'),
            'year' => $request->get('year')
        ], 200);
    }

    // Load project in logbook per PO
    public function load(Request $request)
    {
        $po_id = $request->get('po_id');
        $year = $request->get('year');
        $projects = project::with('user_create_project', 'user_update_project', 'po_has_projects')->where('po_id', $po_id)->get();
        $targets = DB::table('targets')
            ->join('projects', 'targets.project_id', '=', 'projects.id')
            ->join('pos', 'projects.po_id', '=', 'pos.id')
            ->where('projects.po_id', $po_id)
            ->whereNull('projects.deleted_at') //to make sure soft deleted projects not appearing
            ->whereYear('targets.created_at', $year)
            ->get();
        $sprints = Sprint::all();
        return view('pages.logbooks.load', compact('projects', 'targets', 'sprints'));
    }

    // Load backlog for PO
    public function loadBacklogPo()
    {
        $pos = Po::orderBy('name', 'asc')->get();
        $results = DB::table('targets')
            ->join('projects', 'targets.project_id', '=', 'projects.id')
            ->join('pos', 'projects.po_id', '=', 'pos.id')
            ->get();
        return view('pages.logbooks.loadBacklogPo', compact('pos', 'results'));
    }

    // Load project charts per PO
    public function loadBacklogPoCharts(Request $request)
    {
        $po_id = $request->get('po_id');
        $year = $request->get('year');
        $projects = project::with('user_create_project', 'user_update_project', 'po_has_projects', 'target_has_projects')->where('po_id', $po_id)->get();
        if ($po_id) {
            $targets = DB::table('targets')
                ->join('projects', 'targets.project_id', '=', 'projects.id')
                ->join('pos', 'projects.po_id', '=', 'pos.id')
                ->where('projects.po_id', $po_id)
                ->whereYear('targets.created_at', $year)
                ->get();
        }
        $sprints = Sprint::all();
        return view('pages.charts.loadBacklogChart', compact('projects', 'sprints', 'targets'));
    }

    // Get All projects Across PO
    public function loadAllprojectsCharts(Request $request)
    {
        $year = $request->get('year');
        $projects = project::orderBy('name', 'asc')->get();
        $targets = DB::table('targets')
            ->join('projects', 'targets.project_id', '=', 'projects.id')
            ->whereYear('targets.created_at', $year)
            ->get();
        return view('pages.charts.loadAllprojectsCharts', compact('projects', 'targets'));
    }

    // Detail project By Year in Logbook
    public function detailproject(Request $request)
    {
        $sprints = Sprint::all();
        $project_id = $request->get('project_id');
        $year = $request->get('year');
        $targets = Target::where('project_id', $project_id)->whereYear('created_at', $year)->get();
        $project = project::findOrFail($project_id);
        return view('pages.logbooks.detailproject', compact('targets', 'project'));
    }
}