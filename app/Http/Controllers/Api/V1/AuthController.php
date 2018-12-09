<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Auth\AuthService;
use App\Validator\ValidatorException\ValidatorException;

class AuthController extends BaseApiController
{
	public $authService;
	
	public function __construct()
	{
		$this->authService = app(AuthService::class);
	}

    public function register(Request $request){
    	try {
    		$user = $this->authService->handleRegister($request);
	    	return $this->responseWithSuccess($user, trans('message.register.success'), Response::HTTP_OK);
    	} catch (ValidatorException $e) {
    		return $this->responseWithError([], trans('message.register.error.failed'), $e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    	} catch (Exception $e) {
			return $this->responseWithError([], trans('message.server_error'), $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  		
    	}
    }	

    public function login(Request $request)
    {
    	try {
    		$data = $this->authService->handleLogin($request);
	        return $this->responseWithSuccess($data,trans('message.login.success'),Response::HTTP_OK);
    	} catch (ValidatorException $e) {
    		return $this->responseWithError([], trans('message.login.error.failed'), $e->getErrors(), Response::HTTP_UNAUTHORIZED);
    	} catch (Exception $e) {
			return $this->responseWithError([], trans('message.server_error'), $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  		
    	}
    }

}
