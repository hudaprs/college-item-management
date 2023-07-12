<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Sprint;
use App\project;

class SprintController extends Controller
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
        return view('pages.managements.sprints.index');
    }

    public function create()
    {
        $sprint = new Sprint;
        return view('pages.managements.sprints.create', compact('sprint'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
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
                'data' => $sprint
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed creating sprint'
            ], 404);
        }
    }

    public function show($id)
    {
        $sprint = Sprint::with('user_create_sprint', 'user_update_sprint', 'project_sprint')->findOrFail($id);
        return view('pages.managements.sprints.show', compact('sprint'));
    }

    public function edit($id)
    {
        $sprint = Sprint::findOrFail($id);
        return view('pages.managements.sprints.create', compact('sprint'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'project' => 'nullable',
            'name' => 'required',
            'start_date' => 'required',
            'finish_date' => 'required'
        ]);

        $sprint = Sprint::findOrFail($id);
        $sprint->name = $request->input('name');
        $sprint->start_date = $request->input('start_date');
        $sprint->finish_date = $request->input('finish_date');
        $sprint->updated_by = auth()->user()->id;
        $sprint->save();
        $sprint->project_sprint()->sync($request->input('project'));

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Updated',
                'data' => $sprint
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update sprint'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $sprint = Sprint::findOrFail($id);
        $sprint->delete();

        if ($sprint) {
            return response()->json([
                'message' => 'Sprint Deleted',
                'data' => $sprint
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to delete sprint'
            ]);
        }
    }
}