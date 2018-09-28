<?php

declare(strict_types=1);

namespace App\User;

use App\Authentication\Credentials;
use App\User\Exception\UserNotFoundException;
use Dibi\Connection;
use Dibi\Row;
use Ramsey\Uuid\Uuid;

final class UserDataProvider
{

	/**
	 * @var Connection
	 */
	private $dibiConnection;


	public function __construct(Connection $dibiConnection)
	{
		$this->dibiConnection = $dibiConnection;
	}


	public function getUserDataByUsername(string $username): UserData
	{
		$row = $this->dibiConnection->select('*')
			->from('users')
			->where('username = ?', $username)
			->fetch();

		return $this->createUserDataFromRow($row);
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
			new Credentials(
				$row['username'],
				$row['password']
			),
			$row['password_hash']
		);
	}
}
