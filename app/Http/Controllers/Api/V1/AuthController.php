<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
	protected $authService; 

    public function __construct(AuthService $authService)
    {
    	$this->authService = $authService;
    }

    public function register()
    {
    	return $this->authService->registerUser();
    }

    public function login()
    {
    	return $this->authService->loginUser();
    }

    public function logout()
    {
    	return $this->authService->logoutUser();
    }
}
