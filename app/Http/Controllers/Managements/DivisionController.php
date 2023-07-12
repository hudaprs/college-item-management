<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Division;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            if(Gate::allows('C-LEVEL')) return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    public function index()
    {
        return view('pages.managements.divisions.index');
    }

    public function create()
    {   
        $division = new Division;
        return view('pages.managements.divisions.create', compact('division'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $division = new Division;
        $division->name = strtoupper($request->input('name'));
        $division->save();

        if($division) {
            return response()->json([
                'message' => 'Division Has Been Created'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Create Division'
            ], 400);    
        }
    }

    public function show($id)
    {
        $division = Division::findOrFail($id);
        return view('pages.managements.divisions.show', compact('division'));
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('pages.managements.divisions.create', compact('division'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $division = Division::findOrFail($id);
        $division->name = strtoupper($request->input('name'));
        $division->save();

        if($division) {
            return response()->json([
                'message' => 'Division Has Been Updated'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Update Division'
            ], 400);    
        }
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        if($division) {
            return response()->json([
                'message' => 'Division Has Been Removed'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Remove Division'
            ], 200);    
        }
    }
}
