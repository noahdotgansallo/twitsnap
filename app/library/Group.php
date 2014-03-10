<?php

// This is the auth group class

class Group extends DB
{
	protected $table = "groups";

	public function users()
	{
		return $this->hasMany('User');
	}
}