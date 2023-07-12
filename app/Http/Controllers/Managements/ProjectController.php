<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\project;
use App\Sprint;

use DataTables;
use PDF;

class projectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Gate::allows('C-LEVEL'))
                return $next($request);
            abort(403, 'Unauthorized');
        })->except(['projectUpdateStatus']);
    }

    public function index()
    {
        return view('pages.managements.projects.index');
    }

    public function create()
    {
        $project = new project;
        return view('pages.managements.projects.create', compact('project'));
    }

    public function store(Request $request)
    {
        // Validate Form
        $this->validate($request, [
            'superfunprojectId' => 'unique:projects,superfunprojectId',
            'airtableprojectId' => 'nullable|unique:projects,airtableprojectId',
            'po' => 'required',
            'name' => 'required',
            'desc' => 'nullable|string|max:200',
            'actual_date' => 'nullable|date',
            'target_date' => 'nullable|date'
        ]);

        $project = new project;
        $project->superfunprojectId = $request->input('superfunprojectId');
        $project->airtableprojectId = $request->input('airtableprojectId');
        $project->po_id = $request->input('po');
        $project->client_id = $request->input('client');
        $project->project_code = "PRO" . '-' . time();
        $project->name = $request->input('name');
        $project->price = $request->input('price');
        $project->estimated_hours = $request->input('estimated_hours');
        $project->desc = $request->input('desc');
        $project->statusproject = 0;
        $project->actual_date = $request->input('actual_date');
        $project->target_date = $request->input('target_date');
        $project->created_by = auth()->user()->id;
        $project->save();

        $sprints = Sprint::orderBy('name', 'asc')->get();
        foreach ($sprints as $sprint) {
            $project->project_sprint()->attach($sprint->id);
        }

        $project->project_employee()->attach($request->input('employees'));

        if ($project) {
            return response()->json([
                'message' => 'project Created',
                'data' => $project
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create project'
            ], 404);
        }
    }

    public function show($id)
    {
        $project = project::with('user_create_project', 'user_update_project', 'po_has_projects', 'target_has_projects', 'project_sprint', 'project_has_client', 'project_employee')->findOrFail($id);
        return view('pages.managements.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = project::findOrFail($id);
        return view('pages.managements.projects.create', compact('project'));
    }

    public function update(Request $request, $id)
    {
        // Validate Form
        $this->validate($request, [
            'superfunprojectId' => 'unique:projects,superfunprojectId,' . $id,
            'airtableprojectId' => 'nullable|unique:projects,airtableprojectId,' . $id,
            'po' => 'required',
            'name' => 'required',
            'desc' => 'nullable|string|max:200',
            'status' => 'required',
            'actual_date' => 'nullable|date',
            'target_date' => 'nullable|date'
        ]);

        $project = project::findOrFail($id);
        $project->superfunprojectId = $request->input('superfunprojectId');
        $project->airtableprojectId = $request->input('airtableprojectId');
        $project->po_id = $request->input('po');
        $project->client_id = $request->input('client');
        $project->name = $request->input('name');
        $project->desc = $request->input('desc');
        $project->price = $request->input('price');
        $project->estimated_hours = $request->input('estimated_hours');
        $project->statusproject = $request->input('status');
        $project->actual_date = $request->input('actual_date');
        $project->target_date = $request->input('target_date');
        $project->updated_by = auth()->user()->id;
        $project->save();
        $project->project_employee()->sync($request->input('employees'));

        if ($project) {
            return response()->json([
                'message' => 'project Updated',
                'data' => $project
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update project'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $project = project::findOrFail($id);
        $project->delete();

        if ($project) {
            return response()->json([
                'message' => 'project has been removed',
                'data' => $project
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to remove project'
            ]);
        }
    }

    public function projectUpdateStatus($id)
    {
        $project = project::findOrFail($id);
        if ($project->statusproject == 1) {
            $project->statusproject = 0;
        } else {
            $project->statusproject = 1;
        }
        $project->save();
        $year = date('Y');

        if ($project) {
            return response()->json([
                'message' => 'Status project Updated',
                'po_id' => $project->po_id,
                'year' => $year
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Update Status project'
            ], 400);
        }
    }
}