<?php

class UsersController extends Controller
{
	public function index($f3){
		$users = [
			'user1' => 'joe-bob',
			'user2' => 'jimbo'
		];

		$f3->set('response', $users);
		echo \Template::instance()->render('json.php');
	}
}