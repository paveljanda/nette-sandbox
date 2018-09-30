<?php

namespace App\Forms;

use App\User\Exception\DuplicateNameException;
use App\User\UserData;
use App\User\UserDataStorage;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;


final class SignUpFormFactory
{

	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var UserDataStorage */
	private $userDataStorage;


	public function __construct(FormFactory $factory, UserDataStorage $userDataStorage)
	{
		$this->factory = $factory;
		$this->userDataStorage = $userDataStorage;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Pick a username:')
			->setRequired('Please pick a username.');

		$form->addEmail('email', 'Your e-mail:')
			->setRequired('Please enter your e-mail.');

		$form->addPassword('password', 'Create a password:')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Please create a password.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Sign up');

		$form->onSuccess[] = function(Form $form, $values) use ($onSuccess): void {
			try {
				$this->userDataStorage->store(new UserData(
					Uuid::uuid4(),
					$values->username,
					Passwords::hash($values->password)
				));
			} catch (DuplicateNameException $e) {
				if (!$form['username'] instanceof BaseControl) {
					throw new \UnexpectedValueException;
				}

				$form['username']->addError('Username is already taken.');

				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
