<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Managements\projectService;
use App\Helper\ApiMessageResponse;

class projectController extends Controller
{
    protected $projectService;
    protected $apiMessageResponse;

    public function __construct(projectService $projectService, ApiMessageResponse $apiMessageResponse)
    {
        $this->projectService = $projectService;
        $this->apiMessageResponse = $apiMessageResponse;
    }

    public function index()
    {
        return $this->projectService->getprojects();
    }

    public function store()
    {
        return $this->projectService->storeproject();
    }

    public function show($id)
    {
        return $this->projectService->getprojectById($id);
    }

    public function update($id)
    {
        return $this->projectService->updateproject($id);
    }

    public function destroy($id)
    {
        return $this->projectService->removeproject($id);
    }

    public function trashedprojects()
    {
        return $this->projectService->getTrashedprojects();
    }

    public function restore($id)
    {
        return $this->projectService->restoreproject($id);
    }

    public function deletePermanent($id)
    {
        return $this->projectService->deletePermanentproject($id);
    }
}