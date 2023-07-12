<?php 

namespace App\Helper;

use Illuminate\Http\Request;

class ApiMessageResponse
{
	public function successMessage($message =  null, $data = null, $url = null, $method = null)
	{
		return response()->json([
			'message' => $message,
			'data' => $data,
			'url' => $url,
			'method' => $method
		], 200);
	}
}