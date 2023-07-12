<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            if(Gate::allows('C-LEVEL')) return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    // Client View
    public function index()
    {
        return view('pages.managements.clients.index');
    }

    // Create Client View
    public function create()
    {   
        $client = new Client;
        return view('pages.managements.clients.create', compact('client'));
    }

    // Store Client
    public function store(Request $request)
    {
        // Validate Form
        $this->validate($request, [
            'name' => 'required'
        ]);

        $client = new Client;
        $client->name = $request->input('name');
        $client->save();

        if($client) {
            return response()->json([
                'message' => 'Client Has Been Created'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Create Client'
            ], 400);    
        }
    }

    // Detail Client
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('pages.managements.clients.show', compact('client'));
    }

    // Edit Client View
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('pages.managements.clients.create', compact('client'));
    }

    // Update Client
    public function update(Request $request, $id)
    {
        // Validate Form
        $this->validate($request, [
            'name' => 'required'
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->input('name');
        $client->save();

        if($client) {
            return response()->json([
                'message' => 'Client Has Been Updated'
            ], 200);    
        } else {
            return response()->json([
                'message' => 'Failed To Update Client'
            ], 400);    
        }
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        if($client) {
            return response()->json([
                'message' => 'Client Has Been Removed'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Remove Client'
            ], 200);    
        }
    }
}
