<?php
namespace App\Data;

class UserData
{

	private string $login;
	private string $password;


	public function __construct(array $user_params)
	{
		$this->login = trim(htmlspecialchars($user_params['login']));
		$this->password = trim(htmlspecialchars($user_params['password']));
	}


	public function getLogin():string
	{
		return $this->login;
	}

	public function getPassword():string
	{
		return $this->password;
	}

	

}