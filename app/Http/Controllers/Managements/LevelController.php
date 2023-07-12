<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Level;

class LevelController extends Controller
{
    public function index()
    {
        return view('pages.managements.levels.index');
    }

    public function create()
    {
        $level = new Level;
        return view('pages.managements.levels.create', compact('level'));
    }
   
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $level = new Level;
        $level->name = $request->input('name');
        $level->save();

        if($level) {
            return response()->json([
                'message' => 'Level Has Been Created'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Create Level'
            ], 400);
        }
    }

    public function show($id)
    {
        $level = Level::findOrFail($id);
        return view('pages.managements.levels.show', compact('level'));
    }

    public function edit($id)
    {
        $level = Level::findOrFail($id);
        return view('pages.managements.levels.create', compact('level'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $level = Level::findOrFail($id);
        $level->name = $request->input('name');
        $level->save();

        if($level) {
            return response()->json([
                'message' => 'Level Has Been Updated'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Update Level'
            ], 400);
        }
    }

    
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        if($level) {
            return response()->json([
                'message' => 'Level Has Been Removed'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Remove Level'
            ], 400);
        }
    }
}
