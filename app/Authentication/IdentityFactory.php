<?php

declare(strict_types=1);

namespace App\Authentication;

use App\User\UserData;
use Nette\Security\Identity;


final class IdentityFactory
{

	public function createFromUserData(UserData $userData): Identity
	{
		return new Identity(
			$userData->getUuid()->toString(),
			[],
			[
				'username' => $userData->getCredentials()->getUsername()
			]
		);
	}
}
