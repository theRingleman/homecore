<?php

class Controller {

	public $db;

	public $f3;

	public $params;

	public function __construct($f3)
	{
		$this->f3 = $f3;
		$dbCreds = json_decode(file_get_contents(dirname(__DIR__) . '/creds.json'));

		$this->db = new DB\SQL(
    		'mysql:host=homestorage;port=3306;dbname=homecore',
    		"{$dbCreds->database->username}",
    		"{$dbCreds->database->password}"
		);
	}

	public function beforeroute() 
	{
		$this->params = $this->f3->get('PARAMS');
	}

	public function afterroute() 
	{
		
	}

	public function renderJson($response)
	{
		$this->f3->set('response', $response);
		echo \Template::instance()->render('json.php');
	}
}