<?php

declare(strict_types=1);

namespace App\Authentication;

use App\Authentication\Credentials;
use Dibi\Connection;
use Dibi\Row;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use Nette\Security\Passwords;

final class UserAuthenticator
{

	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_EMAIL = 'email',
		COLUMN_ROLE = 'role';


	/**
	 * @var Connection
	 */
	private $dibiConnection;


	public function __construct(Connection $dibiConnection)
	{
		$this->dibiConnection = $dibiConnection;
	}


	/**
	 * @throws AuthenticationException
	 */
	public function authenticate(Credentials $credentials): Identity
	{
		$row = $this->dibiConnection->select('*')
			->from(self::TABLE_NAME)
			->where('%n = ?', self::COLUMN_NAME, $credentials->getUsername())
			->fetch();

		if (!$row instanceof Row) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($credentials->getPassword(), $row[self::COLUMN_PASSWORD_HASH])) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$this->dibiConnection->update(
				self::TABLE_NAME,
				[self::COLUMN_PASSWORD_HASH => Passwords::hash($credentials->getPassword())]
			)->execute();
		}

		unset($row[self::COLUMN_PASSWORD_HASH]);

		return new Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $row);
	}
}
