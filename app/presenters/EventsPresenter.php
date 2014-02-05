<?php

class EventsPresenter extends BasePresenter
{
  private $repository;
  
  public function startup()
  {
    parent::startup();
    $this->repository = $this->context->eventRepository;
  }
  
  public function RenderDefault()
  {
    $this->template->events = $this->repository->findOncoming();
  }
  
  public function RenderAll()
  {
    $this->template->events = $this->repository->findAll();
  }
}
