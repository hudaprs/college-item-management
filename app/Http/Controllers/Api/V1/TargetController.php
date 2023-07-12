<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Managements\TargetService;

class TargetController extends Controller
{
    protected $targetSerivce;

    public function __construct(TargetService $targetService)
    {
        $this->targetService = $targetService;
    }

    public function index()
    {
        return $this->targetService->getTargets();
    }

    public function store()
    {
        return $this->targetService->storeTarget();
    }

    public function show($id)
    {
        return $this->targetService->getTargetById($id);
    }

    public function destroy($id)
    {
        return $this->targetService->deleteTarget($id);
    }
}
