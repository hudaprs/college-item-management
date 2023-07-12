<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Target;
use App\Http\Resources\Target as TargetResource;

class TargetController extends Controller
{
    // Get All Targets
    public function index()
    {
        $targets = Target::with('target_has_sprints', 'target_has_projects')->paginate(250);
        return TargetResource::collection($targets);
    }

    // Create New Target
    public function store(Request $request)
    {
        // Validate Form
        $this->validate($request, [
            'project_id' => 'required',
            'sprint_id' => 'required'
        ]);

        $name = $request->input('name');
        for ($i = 0; $i < count($name); $i++) {
            if ($name[$i] !== null) {
                $target = new Target;
                $target->project_id = $request->input('project_id');
                $target->sprint_id = $request->input('sprint_id');
                $target->name = $name[$i];
                $target->created_by = auth()->user()->id;
                $target->save();
            }
        }

        if ($target) {
            return response()->json([
                'message' => 'Target Created',
                'data' => $target,
                'url' => 'api/v1/target',
                'method' => 'POST'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed Creating Target',
                'url' => 'api/v1/target',
                'method' => 'POST'
            ], 400);
        }
    }

    // Get Single Target
    public function show($id)
    {
        $target = Target::with('target_has_sprints', 'target_has_projects')->findOrFail($id);
        return new TargetResource($target);
    }

    // Update Target
    public function update(Request $request, $id)
    {
        // Validate Form
        $this->validate($request, [
            'project_id' => 'required',
            'sprint_id' => 'required',
            'status' => 'required'
        ]);

        $target = Target::findOrFail($id);
        $target->project_id = $request->input('project_id');
        $target->sprint_id = $request->input('sprint_id');
        $target->name = $request->input('name');
        $target->status = $request->input('status');
        $target->updated_by = auth()->user()->id;
        $target->save();

        if ($target) {
            return response()->json([
                'message' => 'Target Updated',
                'data' => $target,
                'url' => 'api/v1/target/' . $target->id,
                'method' => 'PUT'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update target',
                'url' => 'api/v1/target/' . $target->id,
                'method' => 'PUT'
            ], 400);
        }
    }

    // Delete Target
    public function destroy($id)
    {
        $target = Target::findOrFail($id);
        $target->delete();

        if ($target) {
            return response()->json([
                'message' => 'Target removed',
                'data' => $target,
                'url' => 'api/v1/target/' . $target->id,
                'method' => 'DELETE'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to remove target',
                'url' => 'api/v1/target/' . $target->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Search Target
    public function searchTargets(Request $request)
    {
        $status = $request->get('status');

        if ($status == "UNDONE") {
            $targets = Target::with('target_has_sprints', 'target_has_projects')->where('status', null)->paginate(250);
        } else {
            $targets = Target::with('target_has_sprints', 'target_has_projects')->paginate(250);
        }

        return TargetResource::collection($targets);
    }
}