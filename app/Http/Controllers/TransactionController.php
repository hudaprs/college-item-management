<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Product;
use DataTables;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Check if request is ajax
        if ($request->ajax()) {
            try {
                $query = Transaction::query();
                if (auth()->user()->role === 'User') {
                    $query->where('user_id', auth()->user()->id);
                }

                return DataTables::of($query)
                    ->addColumn('action', function ($transaction) {
                        return view('layouts.inc._action_temp', [
                            'model' => $transaction,
                            'url_show' => route('transactions.show', $transaction->id),
                        ]);
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return $this->error($e);
            }
        }

        return view('pages.managements.transactions.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->input('product_id'));
            if ($product && $product->stock === 0) {
                return response()->json([
                    'message' => 'Product stock is empty',
                    'data' => $product
                ], 400);
            }

            $transaction = new Transaction;
            $transaction->product_name = $product->name;
            $transaction->product_description = $product->description;
            $transaction->product_price = $product->price;
            $transaction->quantity = 1;
            $transaction->total_price = $product->price;
            $transaction->status = "Pending";
            $transaction->user_id = auth()->user()->id;
            $transaction->save();

            $product->stock = $product->stock -= 1;
            $product->save();


            DB::commit();
            return response()->json([
                'message' => 'Transaction created',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create Transaction',
                'stack' => $e->getMessage()
            ], 500);
        }

    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('pages.managements.transactions.show', compact('transaction'));
    }
}