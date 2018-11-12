<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseApiController;
use App\Validator\AuthValidator;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseApiController
{
	public $authValidate;
	public $user;
	
	public function __construct()
	{
		$this->authValidate = app(AuthValidator::class);
	}

    public function register(Request $request){
    	try {
	    	$validator = $this->authValidate->registerValidate($request->all());
	    	if(!$validator){
	    		$errors = $this->authValidate->validator->errors();
	    		return $this->responseWithError([], trans('message.register.error.failed'), $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
	    	}
	    	$user = User::create([
	    		'name' => $request->name,
	    		'email' => $request->email,
	    		'password' => bcrypt($request->password)
	    	]);
	    	return $this->responseWithSuccess([], trans('message.register.success'), Response::HTTP_OK);
    	} catch (Exception $e) {
			return $this->responseWithError([], trans('message.server_error'), $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  		
    	}
    }	

    public function login(Request $request)
    {
    	try {
	    	$validator = $this->authValidate->loginValidate($request->all());
	    	if(!$validator){
	    		$errors = $this->authValidate->validator->errors();
	    		return $this->responseWithError([], trans('message.login.error.failed'), $errors, 401);
	    	}
	    	$credentials = request(['email', 'password']);
	        if (! $token = auth()->attempt($credentials)) {
	            return $this->responseWithError([], trans('message.login.error.failed'), [], 401);
	        }
	        $user = $this->getCurrentUserInfo();
	        return $this->responseWithSuccess(
	        	[
	        		'access_token' => $token,
	        		'user' => $user,
	        		'expires_in' => auth()->factory()->getTTL()
	        	], 
	        	trans('message.login.success'), 
	        	Response::HTTP_OK
	        );
    	} catch (Exception $e) {
			return $this->responseWithError([], trans('message.server_error'), $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  		
    	}
    }

    public function getCurrentUser()
    {
    	$this->user = auth()->user() ?? null;
    	return $this;
    }

    public function getCurrentUserInfo()
    {
    	$this->getCurrentUser();
    	if(is_null($this->user)){
    		return [];
    	}
    	return [
    		'email' => $this->user->email,
    		'name' => $this->user->name
    	];
    }
}
