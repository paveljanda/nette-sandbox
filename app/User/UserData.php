<?php

declare(strict_types=1);

namespace App\User;

use App\Authentication\Credentials;
use Ramsey\Uuid\UuidInterface;

final class UserData
{

	/**
	 * @var UuidInterface
	 */
	private $uuid;

	/**
	 * @var Credentials
	 */
	private $credentials;

	/**
	 * @var string
	 */
	private $passwordHash;


	public function __construct(
		UuidInterface $uuid,
		Credentials $credentials,
		string $passwordHash
	) {
		$this->uuid = $uuid;
		$this->credentials = $credentials;
		$this->passwordHash = $passwordHash;
	}


	public function getUuid(): UuidInterface
	{
		return $this->uuid;
	}


	public function getCredentials(): Credentials
	{
		return $this->credentials;
	}


	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}
}
