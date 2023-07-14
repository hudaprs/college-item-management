<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Product;
use File;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === "User") {
                return redirect('/dev');
            }

            return $next($request);
        });
    }

    private static function _uploadFile(Request $request)
    {
        $fileNameToStore = "";
        if ($request->hasFile('image')) {
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $ext = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '-' . rand() . '.' . $ext;
            $request->file('image')->move('images/products', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }

        return $fileNameToStore;
    }

    public function index(Request $request)
    {
        // Check if request is ajax
        if ($request->ajax()) {
            try {
                return DataTables::of(Product::query())
                    ->addColumn('action', function ($product) {
                        return view('layouts.inc._action_temp', [
                            'model' => $product,
                            'url_show' => route('products.show', $product->id),
                            'url_edit' => route('products.edit', $product->id),
                            'url_destroy' => route('products.destroy', $product->id),
                        ]);
                    })
                    ->editColumn('image', function ($product) {
                        $url = $product->image === null ? asset('images/users_images/noimage.png') : asset('images/products/' . $product->image);

                        return "<img src='" . $url . "' width='48px' />";
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'image'])
                    ->make(true);
            } catch (\Exception $e) {
                return $this->error($e);
            }
        }

        return view('pages.managements.products.index');
    }

    public function create()
    {
        $product = new Product;
        return view('pages.managements.products.create_edit', compact('product'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'price' => 'required'
        ]);

        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->image = self::_uploadFile($request);
        $product->stock = $request->input('stock');
        $product->price = $request->input('price');
        $product->save();

        if ($product) {
            return response()->json([
                'message' => 'Product created',
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create Product'
            ], 404);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.managements.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.managements.products.create_edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'price' => 'required'
        ]);

        $fileNameToStore = self::_uploadFile($request);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image !== null) {
                File::delete('images/products/' . $product->image);
            }
            $product->image = $fileNameToStore;
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->stock = $request->input('stock');
        $product->price = $request->input('price');
        $product->save();

        if ($product) {
            return response()->json([
                'message' => 'Product updated',
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update Product'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            // Check Image
            if ($product->image !== null) {
                File::delete('images/products/' . $product->image);
            }

            if ($product) {
                return response()->json([
                    'message' => 'Product has been removed',
                    'data' => $product
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed to remove Product'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Failed to remove Product'
            ], 400);
        }
    }
}