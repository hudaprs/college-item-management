<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Managements\SprintService;

class SprintController extends Controller
{
    protected $sprintService;

    public function __construct(SprintService $sprintService)
    {
        $this->sprintService = $sprintService;
    }

    public function index()
    {
        return $this->sprintService->getSprints();
    }

    public function store()
    {
        
    }

    public function show($id)
    {
        return $this->sprintService->getSprintById($id);
    }

    public function update($id)
    {
        return $this->sprintService->updateSprint($id);
    }

    public function destroy($id)
    {
        return $this->sprintService->removeSprint($id);
    }

    public function trashed()
    {
        return $this->sprintService->getTrashedSprints();
    }

    public function restore($id)
    {
        return $this->sprintService->restoreSprint($id);
    }

    public function deletePermanent($id)
    {
        return $this->sprintService->deletePermanentSprint($id);
    }
}
