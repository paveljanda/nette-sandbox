<?php

declare(strict_types=1);

namespace App\User;

use App\User\Exception\UserNotFoundException;
use Dibi\Connection;
use Dibi\Row;
use Ramsey\Uuid\Uuid;

final class UserDataProvider implements IUserDataProvider
{

	/**
	 * @var Connection
	 */
	private $dibiConnection;


	public function __construct(Connection $dibiConnection)
	{
		$this->dibiConnection = $dibiConnection;
	}


	/**
	 * @throws UserNotFoundException
	 */
	public function getUserDataByUsername(string $username): UserData
	{
		$row = $this->dibiConnection->select('*')
			->from('users')
			->where('username = ?', $username)
			->fetch();

		return $this->createUserDataFromRow($row instanceof Row ? $row : null);
	}


	/**
	 * @throws UserNotFoundException
	 */
	private function createUserDataFromRow(?Row $row): UserData
	{
		if ($row === null) {
			throw new UserNotFoundException;
		}

		return new UserData(
			Uuid::fromBytes($row['uuid']),
			$row['username'],
			$row['password_hash']
		);
	}
}
