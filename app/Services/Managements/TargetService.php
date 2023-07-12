<?php

namespace App\Services\Managements;

use Illuminate\Http\Request;
use App\Target;

class TargetService
{
	protected $target;
	protected $request;

	public function __construct(Target $target, Request $request)
	{
		$this->target = $target;
		$this->request = $request;
	}

	public function targetsRelations()
	{
		return $this->target->with(
			'user_create_target',
			'user_update_target',
			'target_has_sprints',
			'target_has_projects',
			'target_has_po'
		)->newQuery();
	}

	public function getTargets()
	{
		return response()->json([
			'message' => 'Targets List',
			'targets' => $this->targetsRelations()->get(),
			'url' => 'api/v1/targets',
			'method' => 'GET'
		], 200);
	}

	public function newTarget()
	{
		return new $this->target;
	}

	public function getTargetById($targetId)
	{
		$target = $this->targetsRelations()->findOrFail($targetId);
		return response()->json([
			'message' => 'Target Detail',
			'targets' => $target,
			'url' => 'api/v1/targets/' . $target->id,
			'method' => 'GET'
		]);
	}

	public function storeTarget()
	{
		$this->request->validate([
			'project_id' => 'required',
			'sprint_id' => 'required',
			'po_id' => 'nullable'
		]);

		$targets = $this->request->input('targets');
		$str = preg_split("/[`]/", $targets); //proses pemisahaan target
		$searchword = 'done';
		for ($i = 1; $i < count($str); $i++) {
			$int = preg_replace('/[*]/', ' ', $str[$i]); //proses pemisahan status target
			$nameTarget = preg_replace('/ done/', '', $int); //proses memisahkan nama target
			$arr = [$int];
			foreach ($arr as $k => $v) {
				if (preg_match("/\b$searchword\b/i", $v)) { //proses pencarian status done dan penginputan
					$target = $this->newTarget();
					$target->project_id = $this->request->input('project_id');
					$target->sprint_id = $this->request->input('sprint_id');
					$target->name = $nameTarget;
					$target->status = "DONE";
					$target->created_by = auth()->user()->id;
					$target->save();
				} else {
					$target = $this->newTarget();
					$target->project_id = $this->request->input('project_id');
					$target->sprint_id = $this->request->input('sprint_id');
					$target->name = $nameTarget;
					$target->created_by = auth()->user()->id;
					$target->save();
				}
			}
		}

		return response()->json([
			'message' => 'Target Created',
			'targets' => $target,
			'url' => 'api/v1/targets',
			'method' => 'POST'
		], 201);
	}

	public function deleteTarget($targetId)
	{
		$target = $this->targetsRelations()->findOrFail($targetId);
		if ($target->delete()) {
			return response()->json([
				'message' => 'Target Deleted',
				'targets' => $target,
				'url' => 'api/v1/targets/' . $target->id,
				'method' => 'DELETE'
			], 200);
		}
	}
}