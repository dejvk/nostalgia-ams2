<?php

class EventsPresenter extends BasePresenter
{
  private $repository;
  private $status;
  
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
  
  public function actionDelete ( $id )
  {
    $this->status = $this->repository->deleteEvent ($id);
  }
  
  public function renderDelete ()
  {
    $this->template->eventDeleteStatus = $this->status;
  }
}
