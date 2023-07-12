<?php

namespace App\Imports;

use App\Target;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TargetsImport implements ToModel
{
	protected $project_id;
	protected $sprint_id;
	protected $created_by;

	public function __construct($projectId, $sprintId, $createdBy)
	{
		$this->project_id = $projectId;
		$this->sprint_id = $sprintId;
		$this->created_by = $createdBy;
	}

	public function model(array $row)
	{
		return new Target([
			'project_id' => $this->project_id,
			'sprint_id' => $this->sprint_id,
			'name' => $row[1],
			'status' => $row[2],
			'created_by' => $this->created_by
		]);
	}
}