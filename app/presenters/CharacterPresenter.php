<?php

class CharactersPresenter extends BasePresenter
{
  private $repository;
  private $char;
  private $chars;
  
  public function startup ()
  {
    parent::startup();
    if ( ! $this->getUser()->isLoggedIn() )
      $this->redirect ("Sign:in");
    $this->repository = $this->context->characterRepository;
  }
  
  /** Render all characters */
  public function renderDefault ()
  {
    $this->template->chars = $this->repository->findAll();
  }
  
  
  public function actionCharacter( $id )
  {
    $this->char = $this->repository->findByName($id)->fetch();
  }

  /** Render single character */
  public function renderCharacter()
  {
    if ($this->char)
      $this->template->char = $this->char;
    else
      $this->template->char = null;
  }
  
  /** Render my characters */
  public function renderMy ()
  {
    $this->template->chars = $this->repository->findByAccount($this->getUser()->getIdentity()->data['id']);
  }
  
  /** Get characters on account */
  public function actionAccount ( $id )
  {
    $this->chars = $this->repository->findByAccountName ( $id );
  }
  
  public function renderAccount ()
  {
    $this->template->chars = $this->chars;
  }
}
