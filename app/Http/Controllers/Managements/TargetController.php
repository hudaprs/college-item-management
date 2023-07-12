<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Target;
use App\Sprint;
use App\project;

class TargetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Gate::allows('C-LEVEL'))
                return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    public function index()
    {
        return view('pages.managements.targets.index');
    }

    public function create()
    {
        $target = new Target;
        $sprints = Sprint::orderBy('name', 'asc')->get();
        $projects = project::orderBy('name', 'asc')->get();
        return view('pages.managements.targets.create', compact('target', 'sprints', 'projects'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'sprint' => 'required'
        ]);

        $name = $request->input('name');
        for ($i = 0; $i < count($name); $i++) {
            if ($name[$i] !== null) {
                $target = new Target;
                $target->project_id = $request->input('project');
                $target->sprint_id = $request->input('sprint');
                $target->name = $name[$i];
                $target->created_by = auth()->user()->id;
                $target->save();
            }
        }

        if ($target) {
            return response()->json([
                'message' => 'Target created'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create target'
            ], 404);
        }
    }

    public function show($id)
    {
        $target = Target::with('target_has_sprints', 'user_create_target', 'user_update_target')->findOrFail($id);
        $project = project::findOrFail($target->project_id);
        return view('pages.managements.targets.show', compact('target', 'project'));
    }

    public function edit($id)
    {
        $target = Target::findOrFail($id);
        $sprints = Sprint::orderBy('name', 'asc')->get();
        $projects = project::orderBy('name', 'asc')->get();
        return view('pages.managements.targets.create', compact('target', 'sprints', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'project' => 'required',
            'sprint' => 'required',
            'status' => 'nullable'
        ]);

        $name = $request->input('name');
        $status = $request->input('status');

        $target = Target::findOrFail($id);
        $target->project_id = $request->input('project');
        $target->sprint_id = $request->input('sprint');
        $target->name = $request->input('name');
        $target->status = $request->input('status');
        $target->updated_by = auth()->user()->id;
        $target->save();

        if ($target) {
            return response()->json([
                'message' => 'Target Updated',
                'data' => $target
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update target'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $target = Target::findOrFail($id);
        $target->delete();

        if ($target) {
            return response()->json([
                'message' => 'Target removed',
                'po_id' => 13,
                'year' => date('Y'),
                'target_id' => $target->id
            ], 200);
        }
    }

    public function updateTarget($id)
    {
        $target = Target::findOrFail($id);
        if ($target->status == "DONE") {
            $target->status = null;
        } else {
            $target->status = "DONE";
        }

        if ($target->save()) {
            return response()->json([
                'message' => 'Target Has Been Updated',
                'po_id' => 13,
                'year' => date('Y'),
                'target_id' => $target->id,
                'status' => $target->status
            ], 200);
        }
    }

    public function updateTargetName(Request $request, $id)
    {
        $target = Target::findOrFail($id);
        $target->name = $request->get('name');
        if ($target->save()) {
            return response()->json([
                'message' => 'Target Has Been Updated'
            ], 200);
        }
    }

    public function addNewTargets(Request $request)
    {

        $targets = $request->input('name');
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
                        $target->sprint_id = $request->input('sprint_id');
                        $target->name = $nameTarget;
                        $target->status = "DONE";
                        $target->created_by = auth()->user()->id;
                        $target->created_at = date($request->get('year') . '-m-d H:i:s');
                        $target->save();
                    } else {
                        $target = new Target;
                        $target->project_id = $request->input('project_id');
                        $target->sprint_id = $request->input('sprint_id');
                        $target->name = $nameTarget;
                        $target->created_by = auth()->user()->id;
                        $target->created_at = date($request->get('year') . '-m-d H:i:s');
                        $target->save();
                    }
                }
            }
        }
        return response()->json([
            'message' => 'Targets Has Been Added',
            'po_id' => $request->get('po_id'),
            'year' => $request->get('year')
        ], 200);
    }
}