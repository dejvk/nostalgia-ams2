<?php
/**
 * Created by PhpStorm.
 * User: Dejv
 * Date: 14.5.14
 * Time: 12:14
 */

class GuildsPresenter extends BasePresenter {

	protected $repository;

	public function startup ()
	{
		parent::startup();
		if ( ! $this->getUser()->isLoggedIn() )
			$this->redirect ("Sign:in");
		$this->repository = $this->context->guildRepository;
	}

	public function renderDefault ()
	{
		$this->template->guilds = $this->repository->findAll();
	}

	public function renderGuild ($id)
	{
		$this->template->members = $this->repository->findMembers($id);
	}
} 