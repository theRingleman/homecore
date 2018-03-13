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

	public function findById($id){
		$this->load(['id=?', $id]);
		return $this->query;
	}

	public function all() 
	{
		$this->load();
		return $this->query;
	}

	public function edit($id, $attributes)
	{
		$this->load(['id=?', $id]);
		$this->copyFrom($attributes);
		$this->update();
	}

	public function create($attributes)
	{
		$this->copyFrom($attributes);
		$this->save();
	}

	public function delete($id)
	{
		$this->load(['id' => $id]);
		$this->erase();
	}
}