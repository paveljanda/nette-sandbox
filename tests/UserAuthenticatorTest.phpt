<?php

declare(strict_types=1);

namespace Tests;

use App\Authentication\Credentials;
use App\Authentication\UserAuthenticator;
use App\User\Exception\UserNotFoundException;
use App\User\IUserDataProvider;
use App\User\UserData;
use Mockery;
use Nette\DI\Container;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';

class UserAuthenticatorTest extends TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var UserData
	 */
	private $userData;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	public function setUp(): void
	{
		parent::setUp();

		$this->userData = new UserData(Uuid::uuid4(), 'franta', 'passHash');
	}


	public function testUserNotFound(): void
	{
		$userDataProvider = Mockery::mock(IUserDataProvider::class)
			->shouldReceive('getUserDataByUsername')
			->with('franta')
			->andThrow(new UserNotFoundException)
			->getMock();

		$userAuthenticator = new UserAuthenticator($userDataProvider);

		Assert::exception(
			function() use ($userAuthenticator): void {
				$userAuthenticator->authenticate(new Credentials('franta', 'pass'));
			},
			AuthenticationException::class,
			'The username is incorrect.',
			UserAuthenticator::IDENTITY_NOT_FOUND
		);
	}


	public function testInvalidPassword(): void
	{
		$userAuthenticator = new UserAuthenticator($this->getUserDataProvider());

		Assert::exception(
			function() use ($userAuthenticator): void {
				$userAuthenticator->authenticate(new Credentials('franta', 'pass'));
			},
			AuthenticationException::class,
			'The password is incorrect.',
			UserAuthenticator::INVALID_CREDENTIAL
		);
	}


	private function getUserDataProvider(): IUserDataProvider
	{
		return Mockery::mock(IUserDataProvider::class)
			->shouldReceive('getUserDataByUsername')
			->with('franta')
			->andReturn($this->userData)
			->shouldReceive('getPasswordHash')
			->andReturn(Passwords::hash('pass'))
			->getMock();
	}
}

(new UserAuthenticatorTest($container))->run();
