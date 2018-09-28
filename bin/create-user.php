<?php

declare(strict_types=1);

use App\User\Exception\DuplicateNameException;
use App\User\UserDataStorage;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;

if (!isset($_SERVER['argv'][2])) {
	echo '
Add new user to database.

Usage: create-user.php <name> <password>
';
	exit(1);
}

list(, $name, $password) = $_SERVER['argv'];

$container = require __DIR__ . '/../app/bootstrap.php';

/**
 * @var UserDataStorage
 */
$userDataStorage = $container->getByType(UserDataStorage::class);

try {
	$userDataStorage->store(new UserData(
		Uuid::uuid4(),
		$name,
		Passwords::hash($password)
	));

	echo "User $name was added.\n";

} catch (DuplicateNameException $e) {
	echo "Error: duplicate name.\n";
	exit(1);
}
