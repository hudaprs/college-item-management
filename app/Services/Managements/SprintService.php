<?php

namespace App\Services\Managements;

use Illuminate\Http\Request;
use App\Helper\ApiMessageResponse;
use App\Sprint;

class SprintService
{
	protected $sprint;
	protected $request;
	protected $ApiMessageResponse;

	public function __construct(Sprint $sprint, Request $request, ApiMessageResponse $apiMessageResponse)
	{
		$this->sprint = $sprint;
		$this->request = $request;
		$this->apiMessageResponse = $apiMessageResponse;
	}

	public function sprintsRelations()
	{
		return $this->sprint->with(
			'project_sprint',
			'sprint_has_targets'
		)->newQuery();
	}

	public function getSprints()
	{
		return $this->apiMessageResponse->successMessage(
			'Sprints List',
			$this->sprintsRelations()->get(),
			'api/v1/sprints',
			'GET'
		);
	}

	public function newSprint()
	{
		return new $this->sprint;
	}

	public function getSprintById($sprintId)
	{
		return $this->sprintsRelations()->findOrFail($sprintId);
	}

	public function updateSprint($sprintId)
	{
		$this->request->validate([
			'name' => 'required',
			'start_date' => 'nullable',
			'finish_date' => 'nullable',
		]);

		$sprint = $this->sprintsRelations()->findOrFail($sprintId);
		$sprint->name = $this->request->input('name');
		$sprint->start_date = $this->request->input('start_date');
		$sprint->finish_date = $this->request->input('finish_date');
		$sprint->updated_by = auth()->user()->id;

		if ($sprint->save()) {
			$sprint->project_sprint()->sync($this->request->input('projects'));
			return $this->apiMessageResponse->successMessage(
				'Sprint Updated',
				$sprint,
				'api/v1/sprints/' . $sprint->id,
				'PUT'
			);
		}
	}

	public function removeSprint($sprintId)
	{
		$sprint = $this->sprintsRelations()->findOrFail($sprintId);
		if ($sprint->delete()) {
			return $this->apiMessageResponse->successMessage(
				'Sprint Removed',
				$sprint,
				'api/v1/sprints/' . $sprint->id,
				'DELETE'
			);
		}
	}

	public function getTrashedSprints()
	{
		return $this->apiMessageResponse->successMessage(
			'Sprints Trashed List',
			$this->sprintsRelations()->onlyTrashed()->get(),
			'api/v1/sprints/trashed',
			'GET'
		);
	}

	public function restoreSprint($sprintId)
	{
		$sprint = $this->sprintsRelations()->withTrashed()->findOrFail($sprintId);
		if ($sprint->trashed()) {
			if ($sprint->restore()) {
				return $this->apiMessageResponse->successMessage(
					'Sprint Restored',
					$sprint,
					'api/v1/sprints/' . $sprint->id,
					'GET'
				);
			}
		} else {
			return response()->json([
				'message' => 'You cant restore untrashed sprint',
				'url' => 'api/v1/sprints/' . $sprint->id,
				'method' => 'GET'
			], 400);
		}
	}

	public function deletePermanentSprint($sprintId)
	{
		$sprint = $this->sprintsRelations()->withTrashed()->findOrFail($sprintId);

		if ($sprint->trashed()) {
			if ($sprint->forceDelete()) {
				return $this->apiMessageResponse->successMessage(
					'Sprint Deleted Permanently',
					$sprint,
					'api/v1/sprints/' . $sprint->id,
					'DELETE'
				);
			}
		} else {
			return response()->json([
				'message' => 'You cant delete permanent untrashed sprint',
				'url' => 'api/v1/sprints/' . $sprint->id,
				'method' => 'DELETE'
			], 400);
		}
	}
}