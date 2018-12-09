<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Validator\ValidatorException\ValidatorException;
use App\Validator\AuthValidator;
use App\User;

class AuthService extends BaseService
{
	public $authValidate;

	public function __construct()
	{
		$this->authValidate = app(AuthValidator::class);
	}
	public function handleRegister(Request $request)
	{
		$this->validateRegister($request);
    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password)
    	]);
    	return $user;
	}

	public function validateRegister(Request $request)
    {
    	$validator = $this->authValidate->registerValidate($request->all());
    	if(!$validator){
    		$errors = $this->authValidate->validator->errors();
    		throw new ValidatorException($errors);
    	}
    }

    public function handleLogin(Request $request)
	{
		$access_token = $this->validateLogin($request);
        $user = $this->getCurrentUserInfo();
        $expires_in = auth()->factory()->getTTL();
        return compact('access_token', 'user', 'expires_in');
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

    public function validateLogin(Request $request)
    {
    	$validator = $this->authValidate->loginValidate($request->all());
    	if(!$validator){
    		$errors = $this->authValidate->validator->errors();
    		throw new ValidatorException($errors);
    	}
    	$credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
        	throw new ValidatorException();
        }
        return $token;
    }
}