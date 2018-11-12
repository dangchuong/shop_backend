<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
	public function responseWithSuccess($data = [], $message, $status = Response::HTTP_OK)
	{
		return response()->json(['status' => $status, 'data' => $data, 'message' => $message], $status);
	}

	public function responseWithError($data = [], $message, $errors, $status = 500)
	{
		return response()->json(['status' => $status, 'data' => $data, 'message' => $message, 'errors' => $errors], $status);
	}
}
