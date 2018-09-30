<?php

declare(strict_types=1);

namespace App\User;

use App\User\Exception\DuplicateNameException;
use Dibi\Connection;
use Dibi\UniqueConstraintViolationException;

final class UserDataStorage
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
	 * @throws DuplicateNameException
	 */
	public function store(UserData $userData): void
	{
		try {
			$this->dibiConnection->insert(
				'users',
				[
					'uuid' => $userData->getUuid()->getBytes(),
					'username' => $userData->getUsername(),
					'password_hash' => $userData->getPasswordHash(),
				]
			);
		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}
}
