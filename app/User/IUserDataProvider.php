<?php

declare(strict_types=1);

namespace App\User;

use App\User\Exception\UserNotFoundException;

interface IUserDataProvider
{

	/**
	 * @throws UserNotFoundException
	 */
	public function getUserDataByUsername(string $username): UserData;
}
