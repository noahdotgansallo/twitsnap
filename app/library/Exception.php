<?php

class UserDoesNotExist extends Exception
{
	public function errorMessage()
	{
		return 'A user does not exist with the given credentials';
	}
}
class UserWrongPassword extends Exception
{
	public function errorMessage()
	{
		return 'A user exists with the given email, however the password is incorrect';
	}
} 