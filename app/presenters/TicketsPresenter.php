<?php

class TicketsPresenter extends BasePresenter
{
  private $repository;

  
  public function startup ()
  {
    parent::startup();
    if ( ! $this->getUser()->isLoggedIn() )
      $this->redirect ("Sign:in");
    $this->repository = $this->context->characterRepository;
  }
  
  public function renderDefault ()
  {
    $this->template->openTickets = $this->repository->findOpenTickets();
    $this->template->closedTickets = $this->repository->findClosedTickets();
  }
}
