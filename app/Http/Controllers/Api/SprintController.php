<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sprint;
use App\Http\Resources\Sprint as SprintResource;

class SprintController extends Controller
{
    // Get All Sprints
    public function index()
    {
        $sprints = Sprint::with('project_sprint', 'sprint_has_targets')->paginate(250);
        return SprintResource::collection($sprints);
    }

    // Create New Sprint
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'required|date',
            'finish_date' => 'required|date'
        ]);

        $sprint = new Sprint;
        $sprint->name = $request->input('name');
        $sprint->start_date = $request->input('start_date');
        $sprint->finish_date = $request->input('finish_date');
        $sprint->created_by = auth()->user()->id;
        $sprint->save();

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Created',
                'data' => $sprint,
                'url' => 'api/v1/sprint',
                'method' => 'POST'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed creating sprint',
                'url' => 'api/v1/sprint',
                'method' => 'POST'
            ], 404);
        }
    }

    // Get Single Sprint
    public function show($id)
    {
        $sprint = Sprint::with('project_sprint', 'sprint_has_targets')->findOrFail($id);
        return new SprintResource($sprint);
    }

    // Update Sprint
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'required|date',
            'finish_date' => 'required|date'
        ]);

        $sprint = Sprint::findOrFail($id);
        $sprint->name = $request->input('name');
        $sprint->start_date = $request->input('start_date');
        $sprint->finish_date = $request->input('finish_date');
        $sprint->updated_by = auth()->user()->id;
        $sprint->save();

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Updated',
                'data' => $sprint,
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'PUT'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed Updating Sprint',
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'PUT'
            ], 404);
        }
    }

    // Delete Sprint
    public function destroy($id)
    {
        $sprint = Sprint::findOrFail($id);
        $sprint->delete();

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Has Been Moved To Trash',
                'data' => $sprint,
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'DELETE'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Remove Sprint',
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Restore Sprint
    public function restore($id)
    {
        $sprint = Sprint::withTrashed()->findOrFail($id);

        if ($sprint->trashed()) {
            $sprint->restore();
        } else {
            return response()->json([
                'message' => 'You Cant Restore Untrashed Sprint',
                'url' => 'api/v1/sprint/restore/' . $sprint->id,
                'method' => 'GET'
            ], 400);
        }


        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Has Been Restored',
                'data' => $sprint,
                'url' => 'api/v1/sprint/restore/' . $sprint->id,
                'method' => 'GET',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Restore Sprint',
                'url' => 'api/v1/sprint/restore/' . $sprint->id,
                'method' => 'GET'
            ], 400);
        }
    }

    // Delete Permanent Sprint
    public function deletePermanent($id)
    {
        $sprint = Sprint::withTrashed()->findOrFail($id);
        $sprint->forceDelete();

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Has Been Deleted Permanently',
                'data' => $sprint,
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'DELETE',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Delete Permanent Sprint',
                'url' => 'api/v1/sprint/' . $sprint->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Search Sprint
    public function searchSprints(Request $request)
    {
        $name = $request->get('name');
        $start_date = $request->get('start_date');
        $finish_date = $request->get('finish_date');

        $sprints = Sprint::with('project_sprint', 'sprint_has_targets')->where('name', 'LIKE', '%' . $name . '%')->paginate(250);

        // if you search by date
        if (!empty($start_date) && !empty($finish_date)) {
            $sprints = Sprint::with('project_sprint', 'sprint_has_targets')->where('start_Date', '>=', $start_Date)->where('finish_date', '<=', $finish_date);
        }

        return SprintResource::collection($sprints);
    }

    // Trashed Sprints
    public function sprintsTrashed()
    {
        $sprints = Sprint::with('project_sprint', 'sprint_has_targets')->onlyTrashed()->orderBy('name', 'asc')->paginate(250);
        return SprintResource::collection($sprints);
    }
}