<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Price;

class PriceController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            if(Gate::allows('C-LEVEL')) return $next($request);
            abort(403, 'Unauthorized Page');
        });
    }
    
    public function index()
    {
        return view('pages.managements.prices.index');
    }

    public function create()
    {
        $price = new Price;
        return view('pages.managements.prices.create', compact('price'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'price' => 'required|numeric'
        ]);

        $price = new Price;
        $price->price = $request->input('price');
        $price->description = $request->input('description');
        $price->created_by = auth()->user()->id;
        if($price->save()) {
            return response()->json([
                'message' => 'Price Has Been Created'
            ], 200);
        }
    }

    public function show($id)
    {
        $price = Price::with('user_create_price', 'user_update_price')->findOrFail($id);
        return view('pages.managements.prices.show', compact('price'));
    }

    
    public function edit($id)
    {
        $price = Price::with('user_create_price', 'user_update_price')->findOrFail($id);
        return view('pages.managements.prices.create', compact('price'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required|numeric'
        ]);

        $price = Price::findOrFail($id);
        $price->price = $request->input('price');
        $price->description = $request->input('description');
        $price->updated_by = auth()->user()->id;
        if($price->save()) {
            return response()->json([
                'message' => 'Price Has Been Updated'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $price = Price::findOrFail($id);
        if($price->delete()) {
            return response()->json([
                'message' => 'Price Has Been Removed'
            ], 200);
        }
    }
}
