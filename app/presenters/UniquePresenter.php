<?php
use Nette\Application\UI\Form;

class UniquePresenter extends BasePresenter
{
  /** @var AMS\UniqueRepository */
  private $uniqueRepository;
  /** @var Nette\Database\Table\ActiveRow */
  private $request;
  private $votes;
  private $votesCnt;

  
  protected function startup()
  {
    parent::startup();
    if (!$this->getUser()->isLoggedIn())
      $this->redirect('Sign:in');
    $this->uniqueRepository = $this->context->uniqueRepository;
  }
  
  
  
  /** Seznam všech žádostí */
  public function renderAll()
  {
    $this->template->uniques = $this->uniqueRepository->findAll();
  }

  
  
  /** Seznam nevyřešených žádostí */
	public function renderDefault()
	{
		$this->template->uniques = $this->uniqueRepository->findOpen();
	}
  
  
  
  /** Jedna konkrétní žádost */
  public function actionRequest ( $id )
  {
    $this->request = $this->uniqueRepository->find($id)->fetch();
    if ($this->request === FALSE || $this->request == NULL)
      $this->setView('notFound');
    
    $this->votes = $this->uniqueRepository->findVotesForRequest($id);
    $this->votesCnt = $this->uniqueRepository->findVotesCnt($id);
  }
  
  public function renderRequest ()
  {
    $this->template->request = $this->request;
    $this->template->votes = $this->votes;
    $this->template->votesCnt = $this->votesCnt;
  }
  



  /** Formulář pro novou žádost */
  protected function createComponentUniqueForm()
  {
    if (!$this->getUser()->isLoggedIn())
      $this->redirect('Sign:in');

    $form = new Form();
    $form->addText('itemName', 'Název itemu:', 40, 100)
          ->addRule(Form::FILLED, 'Je nutné zadat název itemu.');
    $form->addText('itemId', 'ID itemu:', 40, 10)
          ->addRule(Form::INTEGER, 'Špatně zadané ID itemu.')
          ->addRule(Form::FILLED, 'Je nutné zadat ID itemu.');
    $form->addTextArea('itemDesc', 'Popis itemu:');
    $form->addTextArea('material', 'Materiál:');
    $form->addSubmit('submit', 'Odeslat žádost');
    return $form;
  }
  
  
  protected function createComponentUniqueList()
  {
    return new AMS\UniqueListControl($this->uniqueRepository->findOpen());
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
}
