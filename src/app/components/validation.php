<?php

class Validation extends GUMP
{
	public function __construct($model)
	{
		$this->validation_rules = $model->validationRules;
		$this->filter_rules = $model->filterRules;
	}
}