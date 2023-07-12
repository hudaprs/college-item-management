<?php

namespace App\Services\Managements;

use Illuminate\Http\Request;
use App\Helper\ApiMessageResponse;
use App\Po;

class PoService
{
	protected $po;
	protected $request;

	public function __construct(Po $po, Request $request)
	{
		$this->po = $po;
		$this->request = $request;
	}

	public function productOwnerRelations()
	{
		return $this->po->with(
			'po_has_projects',
			'pos_has_targets'
		)
			->orderBy('name', 'asc')
			->newQuery();
	}

	public function getProductOwners()
	{
		return response()->json([
			'message' => 'Product Owner List',
			'pos' => $this->productOwnerRelations()->get(),
			'url' => 'api/v1/pos',
			'method' => 'GET'
		], 200);
	}

	public function newProductOwner()
	{
		return new $this->po;
	}

	public function getProductOwnerById($productOwnerId)
	{
		$po = $this->productOwnerRelations()->findOrFail($productOwnerId);
		return response()->json([
			'message' => 'Product Owner Detail',
			'pos' => $po,
			'url' => 'api/v1/pos/' . $po->id,
			'method' => 'GET'
		], 200);
	}

	public function storeProductOwner()
	{
		$this->request->validate([
			'name' => 'required',
			'po_nip' => 'required|unique:pos,po_nip'
		]);

		$po = $this->newProductOwner();
		$po->name = $this->request->input('name');
		$po->po_nip = $this->request->input('po_nip');
		$po->statusPo = 0;
		$po->created_by = auth()->user()->id;

		if ($po->save()) {
			return response()->json([
				'message' => 'Product Owner Created',
				'pos' => $po,
				'url' => 'api/v1/pos',
				'method' => 'POST'
			], 201);
		} else {
			return response()->json([
				'message' => 'Failed Creating Product Owner',
				'url' => 'api/v1/pos',
				'method' => 'POST'
			], 400);
		}
	}

	public function updateProductOwner($id)
	{
		$this->request->validate([
			'name' => 'required',
			'po_nip' => 'required|unique:pos,po_nip,' . $id
		]);

		$po = $this->productOwnerRelations()->findOrFail($id);
		$po->name = $this->request->input('name');
		$po->po_nip = $this->request->input('po_nip');
		$po->statusPo = $this->request->input('statusPo');
		$po->updated_by = auth()->user()->id;

		if ($po->save()) {
			return response()->json([
				'message' => 'Product Owner Updated',
				'pos' => $po,
				'url' => 'api/v1/pos/' . $po->id,
				'method' => 'PUT'
			], 201);
		} else {
			return response()->json([
				'message' => 'Failed Updating Product Owner',
				'url' => 'api/v1/pos/' . $po->id,
				'method' => 'PUT'
			], 400);
		}
	}

	public function deleteProductOwner($id)
	{
		$po = $this->productOwnerRelations()->findOrFail($id);
		if ($po->delete()) {
			return response()->json([
				'message' => 'Product Owner Deleted',
				'pos' => $po,
				'url' => 'api/v1/pos/' . $po->id,
				'method' => 'DELETE'
			], 200);
		}
	}
}