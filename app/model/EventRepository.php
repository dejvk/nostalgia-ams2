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
      SELECT name, description, datetime
      FROM _wp_calendar
      WHERE datetime >= '" . date("Y-m-d") . " 00:00:00'
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
}