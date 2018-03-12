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

	public function all() 
	{
		$this->load();
		return $this->query;
	}

	public function getById($id)
	{
		$this->load(['id=?', $id]);
		return $this->query;
	}
}