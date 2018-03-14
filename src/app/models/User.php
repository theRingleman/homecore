<?php

class User extends Model
{
	public $attributes = [
		"id",
		"username",
		"firstname",
		"lastname"
	];

	public $validationRules = [
		'username' => 'required|alpha_numeric',
		'firstname' => 'max_len,32|min_len,2|alpha',
		'lastname' => 'max_len,32|min_len,2|alpha',
	];

	public $filterRules = [
		'username' => 'trim|sanitize_string',
		'firstname' => 'trim|sanitize_string',
		'lastname' => 'trim|sanitize_string'
	];

	public function __construct($db)
	{
		parent::__construct($db, 'Users');
	}

}