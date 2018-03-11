<?php

class Model extends DB\SQL\Mapper
{
	public $attributes = [];

	public function toEndPoint(){
		$attributes = [];
		foreach ($this->attributes as $attribute) {
			$attributes[$attribute] = $this->{$attribute};
		}
		return $attributes;
	}
}