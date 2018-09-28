<?php

declare(strict_types=1);

namespace App\User;

use Ramsey\Uuid\UuidInterface;

final class UserData
{

	/**
	 * @var UuidInterface
	 */
	private $uuid;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $passwordHash;


	public function __construct(
		UuidInterface $uuid,
		string $username,
		string $passwordHash
	) {
		$this->uuid = $uuid;
		$this->username = $username;
		$this->passwordHash = $passwordHash;
	}


	public function getUuid(): UuidInterface
	{
		return $this->uuid;
	}


	public function getUsername(): string
	{
		return $this->username;
	}


	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}
}
