<?php

class Controller {

	public $db;

	public $f3;

	public $params;

	public function __construct($f3)
	{
		$this->f3 = $f3;
		$this->db = $f3->get("DB");
	}

	public function beforeroute() 
	{
		$this->params = $this->f3->get('PARAMS');
		if ($this->f3->exists('BODY')) {
			$this->attributes = json_decode($this->f3->get('BODY'));	
		}
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