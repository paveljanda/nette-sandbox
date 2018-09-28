<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Exception\DuplicateNameException;
use Dibi\Connection;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;

final class UserManager
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
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
				self::COLUMN_EMAIL => $email,
			]);
		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}
}
