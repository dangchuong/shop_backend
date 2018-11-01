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
	
	public function __construct()
	{
		$this->authValidate = app(AuthValidator::class);
	}

    public function register(Request $request){
    	try {
	    	$validator = $this->authValidate->registerValidate($request->all());
	    	if(!$validator){
	    		$errors = $this->authValidate->validator->errors();
	    		return $this->responseWithError([], 'Register faild', $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
	    	}
	    	$user = User::create([
	    		'name' => $request->name,
	    		'email' => $request->email,
	    		'password' => encrypt($request->password)
	    	]);
	    	return $this->responseWithSuccess([], 'Register Success', Response::HTTP_OK);
    	} catch (Exception $e) {
			return $this->responseWithError([], 'Server error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);  		
    	}
    }	
}
