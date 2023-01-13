<?php
namespace App\Data;

class TokenResponse
{
	private string $token_key;
	private int $token_untill;


	public function setTokenKey(string $token_key):self
	{
		$this->token_key = $token_key;

		return $this;
	}	

	public function getTokenKey():string
	{
		return $this->token_key;
	}


	public function setTokenUntill(int $token_untill):self
	{
		$this->token_untill = $token_untill;

		return $this;
	}


	public function getTokenUntill():int
	{
		return $this->token_untill;
	}

	
}