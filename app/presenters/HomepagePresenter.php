<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

  /** @var AMS\UniqueRepository */
  private $eventRepository;
  
  protected function startup()
  {
    parent::startup();
    $this->eventRepository = $this->context->eventRepository;
  }

	public function renderDefault()
	{
		$this->template->events = $this->eventRepository->findOncoming(5);
	}

}
