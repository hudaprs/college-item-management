<?php 

namespace App\Services;

use Illuminate\Http\Request;
use App\Helper\ApiMessageResponse;
use App\User;
use Hash;
use Auth;

class AuthService
{
	protected $user;
	protected $request;
	protected $apiMessageResponse;

	public function __construct(User $user, Request $request, ApiMessageResponse $apiMessageResponse)
	{
		$this->user = $user;
		$this->request = $request;
		$this->apiMessageResponse = $apiMessageResponse;
	}

	public function newUser()
	{
		return new $this->user;
	}

	public function registerUser()
	{
		$this->request->validate([
	        'nip' => 'required|unique:users,nip',
	        'phone' => 'required|digits_between:1,12',
	        'name' => 'required',
	        'email' => 'required|email|unique:users,email',
	        'password' => 'required|min:8|max:20', 
	        'password_confirmation' => 'required|same:password'
		]);

		$user = new $this->user;
		$user->image = "noimage.png";
		$user->nip = $this->request->input('nip');
		$user->phone = preg_replace('/\s+/', '', $this->request->input('phone'));
		$user->name = ucwords($this->request->input('name'));
		$user->email = preg_replace('/\s+/', '', strtolower($this->request->input('email')));
		$user->password = \Hash::make($this->request->input('password'));
		$user->created_by = 1;

		if($user->save()) {
			return $this->apiMessageResponse->successMessage(
				'Register Success',
				$user,
				'api/v1/register',
				'POST'
			);
		}
	}

	public function loginUser()
	{
		$this->request->validate([
    		'email' => 'required',
    		'password' => 'required'
    	]);

    	$user = $this->user->where('email', $this->request->email)->first();
    	$message = "";
    	$data = null;
    	$code = 401;

    	if($user == true && \Hash::check($this->request->password, $user->password)) {
			$user->generateToken();
			$message = "Login Success";
			$status = "OK";
			$data = $user;
			$code = 200;
    	} else {
			$message = "ERROR ( Email / Password Invalid )";
			$user->api_token = null;
			$user->save();
			$data = null;
		}

		return $this->apiMessageResponse->successMessage(
			$message,
			$data,
			'api/v1/login',
			'POST'
		);
	}	

	public function logoutUser()
	{
		$user = Auth::user();
    	if($user) {
    		$user->api_token = null;
    		$user->save();
    	}

    	return $this->apiMessageResponse->successMessage(
    		'Logout Success',
    		$user,
    		'api/v1/logout',
    		'POST'
    	);
	}
}