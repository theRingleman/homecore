<?php

class UsersController extends Controller
{
	public function index(){

		$users = $this->db->exec('SELECT * FROM Users');

		$this->renderJson($users);
	}

	public function show()
	{
		$user = new DB\SQL\Mapper($this->db, 'Users');
		$user->load(['username=?', 'theRingleman']);
		$userEndpoint = [];
		$userEndpoint['username'] = $user->username;
		$userEndpoint['firstanme'] = $user->firstname;
		$userEndpoint['lastname'] = $user->lastname;
		$userEndpoint['id'] = $user->id;
		$this->renderJson($userEndpoint);
	}
}