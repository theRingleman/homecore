<?php

namespace models;

class User extends Model
{
	public $attributes = [
		"id",
		"email",
		"firstname",
		"lastname"
	];

	public $validationRules = [
		'firstname' => 'max_len,32|min_len,2|alpha',
		'lastname' => 'max_len,32|min_len,2|alpha',
        'email' => 'required|valid_email',
        'password' => 'required'
	];

	public $filterRules = [
		'firstname' => 'trim|sanitize_string',
		'lastname' => 'trim|sanitize_string',
		'email' => 'trim|sanitize_email',
        'password' => 'trim'
	];

	public function __construct($db)
	{
		parent::__construct($db, 'Users');
	}
}