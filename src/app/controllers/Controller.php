<?php

class Controller {

	public $db;

	public function __construct()
	{
		$dbCreds = json_decode(file_get_contents(dirname(__DIR__) . '/creds.json'));
		
		$this->db = new DB\SQL(
    		'mysql:host=homestorage;port=3306;dbname=homecore',
    		"{$dbCreds->database->username}",
    		"{$dbCreds->database->password}"
		);
	}

	public function beforeroute($f3) 
	{

	}

	public function afterroute($f3) 
	{
		
	}

	public function renderJson($f3, $response)
	{
		$f3->set('response', $response);
		echo \Template::instance()->render('json.php');
	}
}