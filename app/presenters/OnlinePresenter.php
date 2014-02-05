<?php

class OnlinePresenter extends BasePresenter
{
  private $repository;
  
  public function startup()
  {
    parent::startup();
    $this->repository = $this->context->characterRepository;
  }
  
  /** Presents online characters as table */
  public function RenderDefault()
  {
    $this->template->characters = $this->repository->findOnline();
  }
}
