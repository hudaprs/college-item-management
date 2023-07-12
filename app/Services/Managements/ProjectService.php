<?php

namespace App\Services\Managements;

use Illuminate\Http\Request;
use App\Helper\ApiMessageResponse;
use App\project;
use App\Sprint;

class projectService
{
	protected $project;
	protected $request;
	protected $apiMessageResponse;

	public function __construct(project $project, Request $request, ApiMessageResponse $apiMessageResponse)
	{
		$this->project = $project;
		$this->request = $request;
		$this->apiMessageResponse = $apiMessageResponse;
	}

	public function projectRelations()
	{
		return $this->project->with(
			'po_has_projects',
			'project_sprint',
			'target_has_projects',
			'project_has_client',
			'project_employee'
		)->newQuery();
	}

	public function getprojects()
	{
		return $this->apiMessageResponse->successMessage(
			'project List',
			$this->projectRelations()->orderBy('name', 'asc')->get(),
			'api/v1/projects',
			'GET'
		);
	}

	public function getprojectById($projectId)
	{
		$project = $this->projectRelations()->findOrFail($projectId);
		return $this->apiMessageResponse->successMessage(
			'project Detail ' . $project->name,
			$project,
			'api/v1/projects/' . $project->id,
			'GET'
		);
	}

	public function storeproject()
	{
		$this->request->validate([
			'superfunprojectId' => 'unique:projects,superfunprojectId',
			'po_id' => 'required',
			'name' => 'required',
			'desc' => 'nullable|string|max:200',
			'actual_date' => 'nullable|date',
			'target_date' => 'nullable|date'
		]);

		$project = new $this->project;
		$project->superfunprojectId = $this->request->input('superfunprojectId');
		$project->po_id = $this->request->input('po_id');
		$project->client_id = $this->request->input('client');
		$project->project_code = "PRO" . '-' . time();
		$project->name = $this->request->input('name');
		$project->price = $this->request->input('price');
		$project->estimated_hours = $this->request->input('estimated_hours');
		$project->desc = $this->request->input('desc');
		$project->statusproject = 0;
		$project->actual_date = $this->request->input('actual_date');
		$project->target_date = $this->request->input('target_date');
		$project->created_by = auth()->user()->id;


		if ($project->save()) {
			$sprints = Sprint::orderBy('name', 'asc')->get();
			foreach ($sprints as $sprint) {
				$project->project_sprint()->attach($sprint->id);
			}
			return $this->apiMessageResponse->successMessage(
				'project Created',
				$project,
				'api/v1/projects',
				'POST'
			);
		}
	}

	public function updateproject($projectId)
	{
		$this->request->validate([
			'superfunprojectId' => 'unique:projects,superfunprojectId,' . $projectId,
			'po_id' => 'required',
			'name' => 'required',
			'desc' => 'nullable|string|max:200',
			'status' => 'required',
			'actual_date' => 'nullable|date',
			'target_date' => 'nullable|date'
		]);

		$project = $this->projectRelations()->findOrFail($projectId);
		$project->superfunprojectId = $this->request->input('superfunprojectId');
		$project->po_id = $this->request->input('po_id');
		$project->client_id = $this->request->input('client');
		$project->name = $this->request->input('name');
		$project->desc = $this->request->input('desc');
		$project->price = $this->request->input('price');
		$project->estimated_hours = $this->request->input('estimated_hours');
		$project->statusproject = $this->request->input('status');
		$project->actual_date = $this->request->input('actual_date');
		$project->target_date = $this->request->input('target_date');
		$project->updated_by = auth()->user()->id;

		if ($project->save()) {
			$project->project_employee()->sync($this->request->input('employees'));
			return $this->apiMessageResponse->successMessage(
				'project Updated',
				$project,
				'api/v1/projects/' . $project->id,
				'PUT'
			);
		}
	}

	public function removeproject($projectId)
	{
		$project = $this->projectRelations()->findOrFail($projectId);
		if ($project->delete()) {
			return $this->apiMessageResponse->successMessage(
				'project Removed',
				$project,
				'api/v1/projects/' . $project->id,
				'POST'
			);
		}
	}

	public function getTrashedprojects()
	{
		return $this->apiMessageResponse->successMessage(
			'project Trashed List',
			$this->projectRelations()->orderBy('name', 'asc')->onlyTrashed()->get(),
			'api/v1/projects/trashed',
			'GET'
		);
	}

	public function restoreproject($projectId)
	{
		$project = $this->projectRelations()->withTrashed()->findOrFail($projectId);
		if ($project->trashed()) {
			$project->restore();
			return $this->apiMessageResponse->successMessage(
				'project Restored',
				$project,
				'api/v1/projects/restore/' . $project->id,
				'GET'
			);
		} else {
			return response()->json([
				'message' => 'You Cant Restore Untrashed project!'
			], 400);
		}
	}

	public function deletePermanentproject($projectId)
	{
		$project = $this->projectRelations()->withTrashed()->findOrFail($projectId);
		if ($project->trashed()) {
			$project->forceDelete();
			return $this->apiMessageResponse->successMessage(
				'project Deleted Permanently',
				$project,
				'api/v1/projects/delete-permanent/' . $project->id,
				'DELETE'
			);
		} else {
			return response()->json([
				'message' => 'You Cant Delete Permanent Untrashed project!'
			], 400);
		}
	}
}