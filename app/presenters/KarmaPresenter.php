<?php

class KarmaPresenter extends BasePresenter
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
    $user = $this->getUser()->getIdentity()->data['id'];
    $this->template->karmaReceived = $this->repository->findKarmaReceivedByAccount ( $user );
    $this->template->karmaStats = $this->repository->findKarmaStatistics( $user );
    $this->template->karmaGiven = $this->repository->findKarmaGivenByAccount ( $user );
  }
}
