<?php
namespace AMS;
use Nette;

class UniqueListControl extends Nette\Application\UI\Control
{
  private $selection;
  
  public function __construct ( Nette\Database\Statement $selection )
  {
    parent::__construct();
    $this->selection = $selection;
  }
  
  public function render()
  {
    $this->template->setFile(__DIR__ . '/UniqueList.latte');
    $this->template->uniques = $this->selection;
    $this->template->render();
  }
}
