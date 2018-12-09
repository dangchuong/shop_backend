<?php
namespace App\Validator\ValidatorException;

class ValidatorException extends \Exception
{
	private $errors;

	function __construct($errors = [])
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

}