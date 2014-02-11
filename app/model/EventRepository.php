<?php

namespace AMS;
use Nette;

class EventRepository extends Nette\Object
{
  private $database;
  
  public function __construct ()
  {
    global $container;
    $this->database = $container->nette->database->web;
  }
  
  public function findOncoming ( $limit = 30 )
  {
    return $this->database->query ("
      SELECT id, account, name, description, datetime, category
      FROM _wp_calendar
      WHERE datetime >= '" . date("Y-m-d") . " 00:00:00'
      AND category >= 0
      ORDER BY datetime
      LIMIT 0, $limit
    ");
  }
  
  public function findMy ()
  {
  }
  
  public function findAll ()
  {
    return $this->database->query ("
      SELECT name, description, datetime
      FROM _wp_calendar
      ORDER BY datetime
    ");
  }
  
  public function deleteEvent ( $id )
  {
    global $container;
    $user = $container->getService('user');
    if ( ! $user->isLoggedIn() )
      return "ErrUserNotLogged";

    $q = $this->database->query ("SELECT id, name, account
                                  FROM _wp_calendar
                                  WHERE id = '$id'");
    if ($q->getRowCount() == 0)
      return "ErrEventNotFound";
    $qr = $q->fetch();
    if ($qr['account'] != $user->getIdentity()->data['id'] && $user->getIdentity()->data['gmlevel2'] < 4)
      return "ErrNotOwnEvent";
    
    $this->database->query ("UPDATE _wp_calendar SET category = -1 WHERE id = '$id'");
    return "OK";
  }
}