<?php
namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\{User, Token};
use App\Repository\{UserRepository,TokenRepository};
use App\Data\{UserData, TokenResponse};
use App\Exception\LoginException;
use Symfony\Component\Security\Core\User\UserInterface;


class LoginService
{
	private UserRepository $userRepository;
	private TokenRepository $tokenRepository;
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserRepository $userRepository, TokenRepository $tokenRepository, UserPasswordHasherInterface $passwordHasher)
	{
		$this->userRepository = $userRepository;
		$this->tokenRepository = $tokenRepository;
		$this->passwordHasher = $passwordHasher;
	}



	public function login(UserData $userData):TokenResponse
	{
		$user = null;
		$token = null;
		$old_token = null;

		$token_key = '';

		$login = $userData->getLogin();
		$password = $userData->getPassword();

		$token_livetime = 3800;

		$user = $this->userRepository->getUserByLogin($login);

		if (!$user || !$this->passwordHasher->isPasswordValid($user, $password))
		{
			throw new LoginException("Неправильный логин или пароль");
		}

		$old_token = $this->userRepository->getUserToken($user);

		if ($old_token)
		{
			$this->tokenRepository->remove($old_token, true);
		}

		$token_key = $this->generateTokenKey();
		$token = (new Token())->setUserRel($user)->setTokenKey($token_key)->setTokenUntill( time() + $token_livetime);

		$this->tokenRepository->save($token, true);

		return (new TokenResponse())->setTokenKey($token->getTokenKey())->setTokenUntill($token->getTokenUntill());



	}

	public function logout(UserInterface $currentUser):array
	{
		$token = $this->userRepository->getUserToken($currentUser);

		$this->tokenRepository->remove($token, true);

		return ['message'=> 'Вы успешно вышли из системы'];

	}




	private function generateTokenKey():string
	{
		$token_len = 25;
		$token_string = '';
		$current_char_code = 0;

		for($i = 0; $i < $token_len; $i++)
		{

			if ($i !== 0 && ($i % 5 === 0))
			{
				$token_string.= '-';
			}

			$current_char_code = rand(0, 2);

			switch($current_char_code)
			{
				case 0:
					$token_string.= (string)rand(0, 9);
				break;

				case 1:
					$token_string.= chr(rand(65, 90));
				break;

				case 2:
					$token_string.= chr(rand(97, 122));
				break;
			}
		}

		return $token_string;	

	}


}