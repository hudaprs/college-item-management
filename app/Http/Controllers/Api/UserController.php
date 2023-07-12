<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    // Get All Users
    public function index()
    {
        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
                'url' => 'api/v1/users/',
                'method' => 'GET'
            ], 403);
        }

        $users = User::paginate(250);
        return UserResource::collection($users);
    }
    
    // Create New Users
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:2040',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip',
            'name' => 'required',
            'divisi' => 'required',
            'phone' => 'nullable|numeric|digits_between:1,13|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'email_secondary' => 'nullable|email|unique:users,email_secondary',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|same:password',
            'level' => 'required',
            'status' => 'required'
        ]);

        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
                'url' => 'api/v1/user',
                'method' => 'GET'
            ], 403);
        }

        // Handle File Upload
        if($request->hasFile('image')) {
            // Get Full file name with ext
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // Get file name only
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get file ext
            $ext = $request->file('image')->getClientOriginalExtension();
            // File Name To Store
            $fileNameToStore = $fileName . '-' . rand() . '.' . $ext;
            // Path
            $path = $request->file('image')->move('images/users_images', $fileNameToStore);
        } else {
            $fileNameToStore = "noimage.png";
        }
        
        $user = new User;
        $user->image = $fileNameToStore;
        $user->nip = $request->input('nip');
        $user->name = $request->input('name');
        $user->divisi = $request->input('divisi');
        $user->phone = $request->input('phone');
        $user->email = strtolower($request->input('email'));
        $user->email_secondary = strtolower($request->input('email_secondary'));
        $user->password = \Hash::make($request->input('password'));
        $user->level = strtoupper($request->input('level'));
        $user->status = $request->input('status');
        $user->created_by = auth()->user()->id;
        $user->save();

        if($user) {
            return response()->json([
                'message' => 'User Created',
                'data' => $user,
                'url' => 'api/v1/user',
                'method' => 'POST'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed creating user',
                'url' => 'api/v1/user',
                'method' => 'POST'
            ], 404);
        }
    }

   
    // Get Single User
    public function show($id)
    {
        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }

        $user = User::findOrFail($id);
        return new UserResource($user);
    }

   
    // Update User
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:2040',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip,' . $id,
            'name' => 'required',
            'divisi' => 'required',
            'phone' => 'nullable|numeric|digits_between:1,13|unique:users,phone,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'email_secondary' => 'nullable|email|unique:users,email_secondary,' . $id,
            'level' => 'required',
            'status' => 'required'
        ]);

        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }

        // Handle File Upload
        if($request->hasFile('image')) {
            // Get Full file name with ext
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // Get file name only
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get file ext
            $ext = $request->file('image')->getClientOriginalExtension();
            // File Name To Store
            $fileNameToStore = $fileName . '-' . rand() . '.' . $ext;
            // Path
            $path = $request->file('image')->move('images/users_images', $fileNameToStore);
        } else {
            $fileNameToStore = "noimage.png";
        }

        $user = User::findOrFail($id);

        if($request->hasFile('image')) {
            // Delete Previous Image
            if($user->image !== "noimage.png") {
                File::delete('images/users_images/' . $user->image);
            }
            // Replace with new one
            $user->image = $fileNameToStore;
        }
        
        $user->nip = $request->input('nip');
        $user->name = $request->input('name');
        $user->divisi = $request->input('divisi');
        $user->phone = $request->input('phone');
        $user->email = strtolower($request->input('email'));
        $user->email_secondary = strtolower($request->input('email_secondary'));
        $user->level = strtoupper($request->input('level'));
        $user->status = $request->input('status');
        $user->updated_by = auth()->user()->id;
        $user->save();

        if($user) {
            return response()->json([
                'message' => 'User Updated',
                'data' => $user,
                'url' => 'api/v1/user/' . $user->id,
                'method' => 'PUT'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed Updating User',
                'url' => 'api/v1/user/' . $user->id,
                'method' => 'PUT'
            ], 404);
        }
    }

   // Delete User
    public function destroy($id)
    {
        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }

        $user = User::findOrFail($id);

        // Check For an image
        if($user->image !== "noimage.png") {
            File::delete('images/users_images/' . $user->image);
        }

        $user->delete();

        if($user) {
            return response()->json([
                'message' => 'User Removed',
                'data' => $user,
                'url' => 'api/v1/user/' . $user->id,
                'method' => 'DELETE'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Remove User',
                'url' => 'api/v1/user/' . $user->id,
                'method' => 'DELETE'
            ], 400);
        }
    }

    // Search Users
    public function searchUsers(Request $request)
    {
        $name = $request->get('name');
        $divisi = $request->get('divisi');
        $level = $request->get('level');
        $status = $request->get('status');
        $userLevel = auth()->user()->level;
        $arr = ["C-LEVEL", "MANAGER-HR"];
        if(!in_array($userLevel, $arr)) {
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
        
        $users = User::where('name', 'LIKE', '%' . $name . '%')->where('divisi',  $divisi)->where('level', 'LIKE', '%' . $level . '%')->where('status',  $status )->paginate(250);

        return UserResource::collection($users);
    }
}
