<?php
namespace App\Data;

class UserResponse
{
	private string $login;
	private string $password;

	public function setLogin(string $login):self
	{
		$this->login = $login;

		return $this;
	}

	public function getLogin():string
	{
		return $this->login;
	}


	public function setPassword(string $password):self
	{
		$this->password = $password;

		return $this;
	}

	public function getPassword():string
	{
		return $this->password;
	}
}