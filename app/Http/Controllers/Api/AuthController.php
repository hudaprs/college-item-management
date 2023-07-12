<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use File;

class AuthController extends Controller
{
    //LOGIN
    public function login(Request $request) 
    {
        $message = ['exists' => 'Your account didnt exists in database'];

    	$this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ], $message);
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'ACTIVE'])) {
    		return response()->json([
                'message' => 'Login Success',
            ]);
    	} else {
            return response()->json([
                'message' => 'Email / Username is Invalid or Your account INACTIVE',
            ]);
        }
               
    }
    
    //LOGOUT
    // public function logout()
    // {
    //     $this->guard()->logout();
    //     return response()->json([
    //         'message' => 'Logged Out Successfully.'
    //     ], 200);
    // }
    
    //VIEW PROFILE
    public function profile($id)
    {
        $user = User::findOrFail($id);
        if($id == auth()->user()->id){
            return response()->json([
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
    }

    //CHANGE PROFILE
    public function changeProfile(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:2040',
            'nip' => 'required|numeric|digits_between:1,20|unique:users,nip,' . $user->id,
            'name' => 'required',
            'divisi' => 'required',
            'phone' => 'nullable|numeric|digits_between:1,13|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'email_secondary' => 'nullable|email|unique:users,email_secondary,' . $user->id,
            'level' => 'required'
        ]);
        $user = User::findOrFail($id);
        if($id == auth()->user()->id){
            // Handle File Upload
            if($request->hasFile('image')) {
                // Get FIle Name With Ext
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                // Get File Name only
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get FIle Extension
                $extension = $request->file('image')->getClientOriginalExtension();
                // File Name To Store
                $fileNameToStore = $fileName . '-' . rand() . '.' . $extension;
                // Path To Store
                $path = $request->file('image')->move('images/users_images/', $fileNameToStore);
            } else {
                // If User didn't upload an image. use default image (noimage)
                $fileNameToStore = "noimage.png";
            }

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
                    'message' => 'Profile Has Been Updated',
                    'data' => $user,
                    'url' => 'api/v1/user/profile/changeProfile/',
                    'method' => 'PUT'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed To Update Profile',
                    'url' => 'api/v1/user/profile/changeProfile/',
                    'method' => 'PUT'
                ], 404);
            }
        }else{
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
    }

    //CHANGE PASSWORD
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|same:password'
        ]);
        if($id == auth()->user()->id){
            $user->password = \Hash::make($request->input('password'));
            $user->save();

            if($user) {
                return response()->json([
                    'message' => 'Password Changed',
                    'url' => 'api/v1/user/profile/changePassword/',
                    'method' => 'PUT'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed To Change Password',
                    'url' => 'api/v1/user/profile/changePassword/',
                    'method' => 'PUT'
                ], 404);
            }
        }else{
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
    }

    //DELETE PHOTO
    public function deletePhoto($id)
    {
        $user = User::findOrFail($id);
        if($id == auth()->user()->id){
            // Check User Image
            if($user->image !== "noimage.png") {
                File::delete('images/users_images/' . $user->image);
            }

            $user->image = "noimage.png";
            $user->save();
            if($user) {
                return response()->json([
                    'message' => 'Photo Profile Removed',
                    'url' => 'api/v1/user/profile/deletePhoto/',
                    'method' => 'PUT'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed To Remove Photo',
                    'url' => 'api/v1/user/profile/deletePhoto/',
                    'method' => 'PUT'
                ], 404);
            }
        }else{
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
    }

    //DELETE ACCOUNT
    public function deleteAccount($id) 
    {
        $user = User::findOrFail($id);
        if($id == auth()->user()->id){
            $user->delete();
            if($user) {
                return response()->json([
                    'message' => 'Account Has Been Removed',
                    'url' => 'api/v1/user/profile/deleteAccount/',
                    'method' => 'DELETE'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed To Remove Account',
                    'url' => 'api/v1/user/profile/deleteAccount/',
                    'method' => 'DELETE'
                ], 404);
            }
        }else{
            return response()->json([
                'message' => "You Can't Access This Page",
            ], 403);
        }
    }
}
