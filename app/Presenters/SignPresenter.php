<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use Nette\Application\UI\Form;

final class SignPresenter extends BasePresenter
{

	/**
	 * @var string
	 */
	public $backlink = '';

	/**
	 * @var SignInFormFactory
	 */
	private $signInFactory;

	/**
	 * @var SignUpFormFactory
	 */
	private $signUpFactory;


	public function __construct(SignInFormFactory $signInFactory, SignUpFormFactory $signUpFactory)
	{
		parent::__construct();

		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
	}


	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function(): void {
			$this->restoreRequest($this->backlink);

			$this->redirect('Homepage:');
		});
	}


	protected function createComponentSignUpForm(): Form
	{
		return $this->signUpFactory->create(function(): void {
			$this->redirect('Homepage:');
		});
	}


	public function actionOut(): void
	{
		$this->getUser()->logout();
	}
}
