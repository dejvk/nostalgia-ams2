<?php

namespace AMS;
use Nette;

class UniqueRepository extends Nette\Object
{
  /** @var Nette\Database\Connection */
  private $database;
  
  public function __construct(Nette\Database\Connection $database)
	{
    global $container;
		$this->database = $container->nette->database->economics;
	}
  
  
  /** @return Nette\Database\Table\Selection */
  public function findAll ()
  {
    return $this->database->query("SELECT u.*, c.name, e.name AS name_ext
                                    FROM unique_requests u 
                                    JOIN nostalgia_characters.characters c 
                                    ON u.character = c.guid
                                    LEFT JOIN nostalgia_characters.character_extended_data e
                                    ON c.guid = e.guid
                                    ORDER BY last_change DESC");
  }
  
  public function findOpen ( $limit = 100 )
  {
    return $this->database->query("SELECT u.*, c.name, e.name AS name_ext
                                    FROM unique_requests u 
                                    JOIN nostalgia_characters.characters c 
                                    ON u.character = c.guid
                                    LEFT JOIN nostalgia_characters.character_extended_data e
                                    ON c.guid = e.guid
                                    WHERE state IN (0,1,11,12,21)
                                    ORDER BY last_change DESC
                                    LIMIT 0, " . $limit);
  }
  
  public function find ( $uid )
  {
    return $this->database->query("SELECT u.*, c.name, e.name AS name_ext
                                    FROM unique_requests u 
                                    JOIN nostalgia_characters.characters c 
                                    ON u.character = c.guid
                                    LEFT JOIN nostalgia_characters.character_extended_data e
                                    ON c.guid = e.guid
                                    WHERE uid = " . $uid );
  }
  
  public function findVotesForRequest ( $uid )
  {
    return $this->database->query("SELECT *
                                    FROM unique_votes
                                    WHERE request_uid = " . $uid );
  }
  
  public function findVotesCnt ( $uid )
  {
    $pos = $this->database->query("SELECT COUNT(*) AS pos FROM unique_votes WHERE request_uid = " . $uid . " AND vote > 0" )->fetch();
    $pos = $pos['pos'];
    $neg = $this->database->query("SELECT COUNT(*) AS neg FROM unique_votes WHERE request_uid = " . $uid . " AND vote < 0" )->fetch();
    $neg = $neg['neg'];
    return [ $pos, $neg ];
  }
  
  public static function State ( $st )
  {
    switch ( $st )
    {
      case 0: return [ "pending", "Hlasuje se" ];
      case 1: return [ "crafted", "Vyrobeno" ];
      case 2: return [ "closed", "Předáno" ];
      case 20: return [ "declined", "Zamítnuto" ];
      case 21: return [ "approved", "Schváleno" ];
      case 22: return [ "cancelled", "Zrušeno" ];
    }
  }
  
}
