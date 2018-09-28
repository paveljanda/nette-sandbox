<?php

declare(strict_types=1);

namespace App\Authentication;

use App\Authentication\Credentials;
use App\User\Exception\UserNotFoundException;
use App\User\UserData;
use App\User\UserDataProvider;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use Nette\Security\Passwords;

final class UserAuthenticator
{

	/**
	 * @var UserDataProvider
	 */
	private $userDataProvider;


	public function __construct(UserDataProvider $userDataProvider)
	{
		$this->userDataProvider = $userDataProvider;
	}


	/**
	 * @throws AuthenticationException
	 */
	public function authenticate(Credentials $credentials): UserData
	{
		try {
			$userData = $this->userDataProvider->getUserDataByUsername($credentials->getUsername());
		} catch (UserNotFoundException $e) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		}

		if (!Passwords::verify($credentials->getPassword(), $userData->getPasswordHash())) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($userData->getPasswordHash())) {
			/**
			 * @todo Implements and use some PasswordRefresher
			 */
			/*$this->dibiConnection->update(
				self::TABLE_NAME,
				[self::COLUMN_PASSWORD_HASH => Passwords::hash($credentials->getPassword())]
			)->execute();*/
		}

		return $userData;
	}
}
