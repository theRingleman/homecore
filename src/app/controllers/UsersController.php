<?php

class UsersController extends Controller
{
	public function index($f3){

		$users = $this->db->exec('SELECT * FROM Users');

		$this->renderJson($f3, $users);
	}

	// public function ()
}