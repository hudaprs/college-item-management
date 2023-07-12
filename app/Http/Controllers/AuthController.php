<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;


class AuthController extends Controller
{
	public function customLogin(Request $request)
	{
		// Custom Message for exists
		$message = [
			"exists" => "You account dindn't exists in Our database"
		];
		// Validate Form
		$this->validate($request, [
			'email' => 'required|email|exists:users,email',
			'password' => 'required'
		], $message);

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
			return response()->json([
				'message' => 'Sign In Success, You Will Redirected Automatically,'
			], 200);
		} else {
			return response()->json([
				'message' => 'Email / Password Invalid'
			], 400);
		}
	}
}