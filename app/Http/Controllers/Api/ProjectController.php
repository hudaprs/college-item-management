<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\project;
use App\Http\Resources\project as projectResource;
use App\Sprint;

class projectController extends Controller
{
    public function index()
    {
        $projects = project::with('po_has_projects', 'project_sprint', 'target_has_projects', 'project_employee')->orderBy('name', 'asc')->paginate(10);
        return projectResource::collection($projects);
    }

    public function store(Request $request)
    {
        // Validate Form
        $this->validate($request, [
            'po_id' => 'required',
            'name' => 'required',
            'desc' => 'nullable|string|max:200'
        ]);

        $project = new project;
        $project->po_id = $request->input('po_id');
        $project->project_code = "PRO" . '-' . time();
        $project->name = $request->input('name');
        $project->desc = $request->input('desc');
        $project->created_by = auth()->user()->id;
        $project->save();

        $sprints = Sprint::orderBy('name', 'asc')->get();
        foreach ($sprints as $sprint) {
            $project->project_sprint()->attach($sprint->id);
        }

        if ($project) {
            return response()->json([
                'message' => 'project Created',
                'data' => $project,
                'url' => 'api/v1/project',
                'method' => 'POST'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create project',
                'url' => 'api/v1/project',
                'method' => 'POST'
            ], 400);
        }
    }

    // Get Single project
    public function show($id)
    {
        $project = project::with('po_has_projects', 'project_sprint', 'target_has_projects')->orderBy('name', 'asc')->findOrFail($id);
        return new projectResource($project);
    }

    // Update project
    public function update(Request $request, $id)
    {
        // Validate Form
        $this->validate($request, [
            'po_id' => 'required',
            'name' => 'required',
            'desc' => 'nullable|string|max:200'
        ]);

        $project = project::findOrFail($id);
        $project->po_id = $request->input('po_id');
        $project->name = $request->input('name');
        $project->desc = $request->input('desc');
        $project->updated_by = auth()->user()->id;
        $project->save();

        if ($project) {
            return response()->json([
                'message' => 'project Updated',
                'data' => $project,
                'url' => 'api/v1/project/' . $project->id,
                'method' => 'PUT'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update project',
                'url' => 'api/v1/project/' . $project->id,
                'method' => 'PUT'
            ], 400);
        }
    }

    // Delete project
    public function destroy($id)
    {
        $project = project::findOrFail($id);
        $project->delete();

        if ($project) {
            return response()->json([
                'message' => 'project Has Been Moved To Trash',
                'data' => $project,
                'url' => 'api/v1/project/' . $project->id,
                'method' => 'DELETE'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Delete project',
                'url' => 'api/v1/project/' . $project->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Restore project
    public function restore($id)
    {
        $project = project::withTrashed()->findOrFail($id);

        // Check if project is in trash
        if ($project->trashed()) {
            $project->restore();
        } else {
            return response()->json([
                'message' => 'You Cant Restore Untrashed project',
                'url' => 'api/v1/project/restore/' . $project->id,
                'method' => 'GET'
            ], 400);
        }

        if ($project) {
            return response()->json([
                'message' => 'project Restored',
                'data' => $project,
                'url' => 'api/v1/project/restore/' . $project->id,
                'method' => 'GET'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Restore project',
                'url' => 'api/v1/project/restore/' . $project->id,
                'method' => 'GET'
            ], 400);
        }
    }

    // Delete Permanent
    public function deletePermanent($id)
    {
        $project = project::withTrashed()->findOrFail($id);
        $project->forceDelete();

        if ($project) {
            return response()->json([
                'message' => 'project Has Been Deleted Permanently',
                'data' => $project,
                'url' => 'api/v1/project/delete-permanent/' . $project->id,
                'method' => 'DELETE'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Delete Permanent project',
                'url' => 'api/v1/project/delete-permanent/' . $project->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Search project
    public function searchprojects(Request $request)
    {
        $name = $request->get('name');
        $project_code = $request->get('project_code');

        $projects = project::with('po_has_projects', 'project_sprint', 'target_has_projects')->where('name', 'LIKE', '%' . $name . '%')->where('project_code', 'LIKE', '%' . $project_code . '%')->paginate(250);
        return projectResource::collection($projects);
    }

    // Trashed projects
    public function projectsTrashed()
    {
        $projects = project::with('po_has_projects', 'project_sprint', 'target_has_projects')->onlyTrashed()->orderBy('name', 'asc')->paginate(250);
        return projectResource::collection($projects);
    }
}