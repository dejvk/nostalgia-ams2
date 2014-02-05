<?php

namespace AMS;
use Nette;

/**
 * Table account_prophet
 */
class AccountRepository extends Nette\Object
{

	/** @var Nette\Database\Connection */
	private $database;


	public function __construct(Nette\Database\Connection $database)
	{
    global $container;
    $this->database = $container->nette->database->realmd;
	}
	
	public function findAll ()
	{
	  return $this->database->table('account_prophet');
	}
	
	public function findByName ( $username )
	{
	  return $this->database->query("SELECT id, username, sha_pass_hash, gmlevel2, email, rights
	                                  FROM account_prophet
	                                  NATURAL LEFT JOIN nostalgia_economics.unique_users
	                                  WHERE username = '$username'")->fetch();
	}
}
