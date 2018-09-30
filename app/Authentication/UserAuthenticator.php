<?php

declare(strict_types=1);

namespace App\Authentication;

use App\User\Exception\UserNotFoundException;
use App\User\IUserDataProvider;
use App\User\UserData;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;

final class UserAuthenticator
{

	public const IDENTITY_NOT_FOUND = 1;
	public const INVALID_CREDENTIAL = 2;

	/**
	 * @var IUserDataProvider
	 */
	private $userDataProvider;


	public function __construct(IUserDataProvider $userDataProvider)
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
