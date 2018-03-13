<?php

class User extends Model
{
	public $attributes = [
		"id",
		"username",
		"firstname",
		"lastname"
	];

	public function __construct($db)
	{
		parent::__construct($db, 'Users');
	}
}