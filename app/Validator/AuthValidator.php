<?php
namespace App\Validator;

use App\Validator\BaseValidator;
use Validator;

/**
 * 
 */
class AuthValidator extends BaseValidator
{
	public $validator;

	public function registerValidate($inputs)
	{
		$this->validator = Validator::make($inputs, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
        ]);
        return !$this->validator->fails();
	}

	public function loginValidate($inputs)
	{
		$this->validator = Validator::make($inputs, [
            'email' => 'required',
            'password' => 'required',
        ]);
        return !$this->validator->fails();
	}

}