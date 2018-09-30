<?php

declare(strict_types=1);

namespace Tests;

use App\Authentication\IdentityFactory;
use App\User\UserData;
use Nette\Security\Identity;
use Ramsey\Uuid\Uuid;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/bootstrap.php';

class IdentityFactoryTest extends TestCase
{

	public function testCreateFromUserDataSomething(): void
	{
		$uuid = Uuid::uuid4();

		$identityFactory = new IdentityFactory;
		$userData = new UserData($uuid, 'franta', 'passHash');

		$identity = $identityFactory->createFromUserData($userData);

		Assert::type(Identity::class, $identity);
		Assert::same($uuid->toString(), $identity->getId());
		Assert::same([], $identity->getRoles());
		Assert::same(['username' => 'franta'], $identity->getData());
	}
}

(new IdentityFactoryTest)->run();
