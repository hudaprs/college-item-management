<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Po;
use Auth;

class PoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Gate::allows('C-LEVEL'))
                return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    public function index()
    {
        $pos = Po::all();
        return view('pages.managements.pos.index', compact('pos'));
    }

    public function create()
    {
        $po = new Po;
        return view('pages.managements.pos.create', compact('po'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'po_nip' => 'required|unique:pos,po_nip',
            'name' => 'required',
            'status' => 'required'
        ]);

        $po = new Po;
        $po->po_nip = $request->input('po_nip');
        $po->name = $request->input('name');
        $po->statusPo = $request->input('status');
        $po->created_by = Auth::user()->id;
        $po->save();
        if ($po) {
            return response()->json([
                'message' => 'Product Owner Created',
                'data' => $po
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed creating Product owner'
            ], 404);
        }
    }

    public function show($id)
    {
        $po = Po::with('po_has_projects', 'user_create_po', 'user_update_po')->findOrFail($id);
        return view('pages.managements.pos.show', compact('po'));
    }

    public function edit($id)
    {
        $po = Po::findOrFail($id);
        return view('pages.managements.pos.create', compact('po'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'po_nip' => 'required|unique:pos,po_nip,' . $id,
            'name' => 'required',
            'status' => 'required'
        ]);

        $po = Po::findOrFail($id);
        $po->po_nip = $request->input('po_nip');
        $po->name = $request->input('name');
        $po->statusPo = $request->input('status');
        $po->updated_by = Auth::user()->id;

        if ($po->save()) {
            return response()->json([
                'message' => 'Product Owner Updated',
                'data' => $po
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Update Product Owner'
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->level === "STAFF") {
            abort(403, 'You cant access this page');
        }

        $po = Po::findOrFail($id);
        $po->delete();

        if ($po) {
            return response()->json([
                'message' => 'Product Owner Deleted',
                'data' => $po
            ]);
        } else {
            return response()->json([
                'msg' => 'Failed to delete a product owner / Po is assigned to project'
            ]);
        }
    }
}