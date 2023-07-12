<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DataTables;

class UserController extends Controller
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
                return DataTables::of(User::query())
                    ->addColumn('action', function ($user) {
                        return view('layouts.inc._action_temp', [
                            'model' => $user,
                            'url_show' => route('users.show', $user->id),
                            'url_edit' => route('users.edit', $user->id),
                            'url_destroy' => route('users.destroy', $user->id),
                        ]);
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return $this->error($e);
            }
        }

        return view('pages.managements.users.index');
    }

    public function create()
    {
        $user = new User;
        return view('pages.managements.users.create_edit', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|same:password',
            'role' => 'required'
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = strtolower($request->input('email'));
        $user->password = \Hash::make($request->input('password'));
        $user->role = $request->input('role');
        $user->save();

        if ($user) {
            return response()->json([
                'message' => 'User created',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to create User'
            ], 404);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.managements.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.managements.users.create_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = strtolower($request->input('email'));
        $user->role = $request->input('role');
        $user->save();

        if ($user) {
            return response()->json([
                'message' => 'User updated',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update User'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($user) {
            return response()->json([
                'message' => 'User has been removed',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to remove User'
            ], 400);
        }
    }
}