<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Target;
use App\Http\Resources\Target as TargetResource;

class LogbookController extends Controller
{
    public function addSprintTargetproject(Request $request, $id)
    {
        // Validate Form
        $this->validate($request, [
            'sprint_id' => 'required'
        ]);

        $targets = $request->input('manyTarget');
        $str = preg_split("/[`]/", $targets); //proses pemisahaan target
        $searchword = 'done';
        for ($i = 1; $i < count($str); $i++) {
            $int = preg_replace('/[*]/', ' ', $str[$i]); //proses pemisahan status target
            $nameTarget = preg_replace('/ done/', '', $int); //proses memisahkan nama target
            $arr = [$int];
            foreach ($arr as $k => $v) {
                if (preg_match("/\b$searchword\b/i", $v)) { //proses pencarian status done dan penginputan
                    $target = new Target;
                    $target->project_id = $id;
                    $target->sprint_id = $request->input('sprint_id');
                    $target->name = $nameTarget;
                    $target->status = "DONE";
                    $target->created_by = auth()->user()->id;
                    $target->save();
                } else {
                    $target = new Target;
                    $target->project_id = $id;
                    $target->sprint_id = $request->input('sprint_id');
                    $target->name = $nameTarget;
                    $target->created_by = auth()->user()->id;
                    $target->save();
                }
            }
        }

        return response()->json([
            'message' => 'project Updated',
            'data' => $target,
            "url" => "api/v1/logbook/add-sprint-target-project/" . $id,
            "method" => 'PUT'
        ], 200);
    }
}