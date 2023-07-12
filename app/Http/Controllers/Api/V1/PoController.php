<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Managements\PoService;

class PoController extends Controller
{
    protected $poService;

    public function __construct(PoService $poService)
    {
        $this->poService = $poService;
    }

    public function index()
    {
        return $this->poService->getProductOwners();
    }

    public function store()
    {   
        return $this->poService->storeProductOwner();
    }

    public function show($id)
    {
        return $this->poService->getProductOwnerById($id);
    }

    public function update($id)
    {
        return $this->poService->updateProductOwner($id);
    }

    public function destroy($id)
    {
        return $this->poService->deleteProductOwner($id);
    }
}
