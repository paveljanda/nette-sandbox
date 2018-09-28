<?php

declare(strict_types=1);

namespace App\Authentication;

final class Credentials
{

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;


	public function __construct(string $username, string $password)
	{
		$this->username = $username;
		$this->password = $password;
	}


	public function getUsername(): string
	{
		return $this->username;
	}


	public function getPassword(): string
	{
		return $this->password;
	}
}
