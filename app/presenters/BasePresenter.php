<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
  public function beforeRender()
  {
    $this->template->menuitems = 
      [ 
        [ 'name' => "Domovská stránka", 'link' => "/www" ]
      ];
  }
  
  public function handleSignOut ()
  {
    $this->getUser()->logout();
    $this->redirect('Sign:in');
  }
}
