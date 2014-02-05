<?php

namespace AMS;
use Nette;

class CharacterRepository extends Nette\Object
{
  private $database;
  
  public function __construct(Nette\Database\Connection $database)
	{
    global $container;
		$this->database = $container->nette->database->characters;
	}
	
	/** Get all characters */
  public function findAll ()
  {
    return $this->database->query ("SELECT c.guid, c.name, coalesce(e.name, c.name) AS name_ext, e.birth_year, e.description, e.profession, c.race
                                    FROM characters c
                                    JOIN character_extended_data e
                                    ON c.guid = e.guid
                                    ORDER BY name_ext");
  }
  
  /** Get single character by name */
  public function findByName ( $id )
  {
    return $this->database->query ("SELECT c.guid, c.name, coalesce(e.name, c.name) AS name_ext, e.birth_year, e.description, e.profession, c.race
                                    FROM characters c
                                    LEFT JOIN character_extended_data e
                                    ON c.guid = e.guid
                                    WHERE c.name = '$id'");
  }
  
  /** Get characters of account */
  public function findByAccount ( $id )
  {
    return $this->database->query ("SELECT c.guid, c.name, coalesce(e.name, c.name) AS name_ext, e.birth_year, e.description, e.profession, c.race
                                    FROM characters c
                                    LEFT JOIN character_extended_data e
                                    ON c.guid = e.guid
                                    WHERE account = '$id'
                                    ORDER BY name_ext");
  }
  
  public function findOnline ()
  {
    return $this->database->query ("SELECT c.guid, c.name, COALESCE(e.name, c.name) AS name_ext, e.profession, a.username, a.last_ip, c.money, c.totaltime, c.zone, c.race
                                    FROM characters c
                                    JOIN nostalgia_realmd.account_prophet a
                                    ON c.account = a.id
                                    LEFT JOIN character_extended_data e
                                    ON c.guid = e.guid
                                    WHERE c.online = 1 AND c.extra_flags != c.extra_flags|16
                                    ORDER BY COALESCE(e.name, c.name), c.name");
  }
  
  public function findKarmaGivenByAccount ( $id )
  {
    return $this->database->query ("SELECT * FROM karma WHERE hodnotici = '$id' ORDER BY time");
  }
  
  public function findKarmaReceivedByAccount ( $id )
  {
    return $this->database->query ("SELECT * FROM karma WHERE hodnoceny = '$id' ORDER BY time");
  }
  
  public function findOpenTickets ()
  {
    return $this->database->query ("SELECT c.`name`, c.`online`, t.ticket_text, t.response_text, t.ticket_lastchange
                                    FROM character_ticket t
                                    NATURAL JOIN characters c
                                    WHERE response_text IS NULL 
                                    OR response_text = ''");
  }
  
  public function findClosedTickets ()
  {
    return $this->database->query ("SELECT c.`name`, c.`online`, t.ticket_text, t.response_text, t.ticket_lastchange
                                    FROM character_ticket t
                                    NATURAL JOIN characters c
                                    WHERE response_text IS NOT NULL 
                                    AND response_text != ''");
  }
  
  /****        ****/
  
  public function findKarmaStatistics ( $id )
  {
    $karma = $activekarma = 0;
    $q = $this->database->query ("SELECT k.karma_hodnota AS karma, ac.gmlevel2, ac.last_login
                                  FROM karma k
                                  JOIN accounts.account_prophet ac ON (k.hodnotici = ac.id)
                                  WHERE k.hodnoceny = '$id'");

    while ($c = $q -> fetch())
    {
      $karma += $c['karma'];
      if (strtotime($c['last_login']) < strtotime("-90 days") && $c['gmlevel2'] < 4)
          continue; // od hrace
      if ($c['gmlevel2'] >= 4)
        $c['karma'] = 2*$c['karma']; // od gm, zvysuji vahu

      if ($c['karma'] >= 0)
        $activekarma += $c['karma']; // karma je kladna, jsem hotov
      else
        $activekarma = $activekarma + (2*$c['karma']); // karma je zap., zvysuji vahu
    }
    return [ 'karma' => $karma, 'active' => $activekarma ];
  }
  
  
  /****        ****/
  static public function ZoneAsString ( $zone )
  {
    switch ( $zone )
    {
      case 209: // Falkenstein
      case 130: return 'Hvozd';
    }
  }
  
  static public function RaceAsString ( $race )
  {
    switch ( $race )
    {
      case  1: return "Člověk";
      case  2: return "Ork";
      case  3: return "Trpaslík";
      case  4: return "Kaldorei";
      case  5: return "Nemrtvý";
      case  6: return "Shu-halo";
      case  7: return "Gnóm";
      case  8: return "Troll";
      case 10: return "Sin'dorei";
      case 11: return "Quel'dorei";
      default: return null;
    }
  }
}
