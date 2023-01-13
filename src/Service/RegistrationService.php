<?php
namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Exception\RegistrationException;
use App\Exception\UserException\UserAlreadyExistsException;
use App\Data\{UserData, UserResponse};

class RegistrationService
{
	private UserRepository $userRepository;
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
	{
		$this->userRepository = $userRepository;
		$this->passwordHasher = $passwordHasher;
	}



	public function registration(UserData $userData):UserResponse
	{	
		$login = '';
		$password = '';
		$hashed_password = '';



		$this->checkRegistrationLens($userData);
		$this->checkRegistrationData($userData);


		$login = $userData->getLogin();
		$password = $userData->getPassword();

		if ($this->userRepository->getUserByLogin($login))
		{
			throw new UserAlreadyExistsException("Данный пользователь уже существует в системе");
		}


		$user = new User();

		$hashed_password = $this->passwordHasher->hashPassword($user, $password);

		$user->setLogin($login)->setPassword($hashed_password);

		$this->userRepository->save($user, true);

		return (new UserResponse())->setLogin($login)->setPassword($password);
	}	

	


	private function checkRegistrationLens(UserData $userData):void
	{
		$registration_lens = [
			'login' => strlen($userData->getLogin()),
			'password' => strlen($userData->getPassword()),
		];

		$this->checkRegistrationMinLens($registration_lens);
		$this->checkRegistrationMaxLens($registration_lens);



	}


	private function checkRegistrationData(UserData $userData):void
	{
		$registration_data = [
			'login' => $userData->getlogin(),
			'password' => $userData->getPassword(),
		];


		$registration_data_checkers = [
			'login' => fn(string $login):bool => (preg_match("/^[A-Za-z]{1}[A-Za-z0-9_]*$/i", $login)) ? true : false,
			'password' => fn(string $password):bool => (preg_match("/^[A-Za-z0-9]*$/i", $password)) ? true : false,
		];


		$registration_data_exc = [
			'login' => 'Логин должен начинаться хотя бы с одного латинского символа и может состоять только из символов латинского алфавита, цифр и нижнего подчеркивания',
			'password' => 'Пароль может состоять только из символов латинского алфавита и цифр',

		];


		$registration_data_keys = array_keys($registration_data);

		$check_status = false;
		$check_message = '';


		foreach($registration_data_keys as $registration_data_key)
		{
			$check_status = $registration_data_checkers[$registration_data_key]($registration_data[$registration_data_key]);

			if (!$check_status)
			{
				throw new RegistrationException($registration_data_exc[$registration_data_key]);
			}
		}


	}


	private function checkRegistrationMinLens(array $registration_lens):void
	{
		$available_min_lens = [
			'login' => 5,
			'password' => 10,

		];

		$minlen_checkers = [
			'login' => fn(int $login_len, int $available_min_login):bool => ($login_len >= $available_min_login) ? true : false,
			'password' => fn(int $password_len, int $available_min_password):bool => ($password_len >= $available_min_password) ? true : false,

		];

		$minlen_exc = [
			'login' => fn(int $min_login_len):string => "Длина логина не может быть менее $min_login_len символов",
			'password' => fn(int $min_password_len):string => "Длина пароля не может быть менее $min_password_len символов", 

		];


		$this->executeRegistrationLenCheck($registration_lens, $available_min_lens, $minlen_checkers, $minlen_exc);
	}



	private function checkRegistrationMaxLens(array $registration_lens):void
	{
		$available_max_lens = [
			'login' => 25,
			'password' => 50,
		];

		$maxlen_checkers = [
			'login' => fn(int $login_len, int $available_max_login):bool => ($login_len <= $available_max_login) ? true : false,
			'password' => fn(int $password_len, int $available_max_password):bool => ($password_len <= $available_max_password) ? true : false,
		];

		$maxlen_exc = [
			'login' => fn(int $max_login_len):string => "Длина логина не может быть более $max_login_len символов",
			'password' => fn(int $max_password_len):string => "Длина пароля не может быть более $max_password_len символов", 
		];

		$this->executeRegistrationLenCheck($registration_lens, $available_max_lens, $maxlen_checkers, $maxlen_exc);

	}


	private function executeRegistrationLenCheck(array $registration_lens, array $available_lens, array $lens_checkers, array $lens_exc):void
	{
		$registration_len_keys = array_keys($registration_lens);

		$check_status = false;
		$check_message = '';

		foreach ($registration_len_keys as $registration_len_key)
		{
			$check_status = $lens_checkers[$registration_len_key]($registration_lens[$registration_len_key], $available_lens[$registration_len_key]);

			if (!$check_status)
			{
				throw new RegistrationException($lens_exc[$registration_len_key]($available_lens[$registration_len_key]));
			}

		}


	}



}