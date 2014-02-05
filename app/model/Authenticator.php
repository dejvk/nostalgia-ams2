<?php

use Nette\Security,
	Nette\Utils\Strings;


/*
CREATE TABLE users (
	id int(11) NOT NULL AUTO_INCREMENT,
	username varchar(50) NOT NULL,
	password char(60) NOT NULL,
	role varchar(20) NOT NULL,
	PRIMARY KEY (id)
);
*/

/**
 * Users authenticator.
 */
class Authenticator extends Nette\Object implements Security\IAuthenticator
{
	/** @var AMS\AccountRepository */
	private $repository;


	public function __construct(AMS\AccountRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$row = $this->repository->findByName($username);

		if (!$row) {
			throw new Security\AuthenticationException('Přihlášení se nezdařilo.', self::IDENTITY_NOT_FOUND);
		}

		if ($row->sha_pass_hash != $this->calculateHash($username, $password)) {
			throw new Security\AuthenticationException('Přihlášení se nezdařilo.', self::INVALID_CREDENTIAL);
		}

		unset($row['sha_pass_hash']);
		return new Nette\Security\Identity($row->id, $row->gmlevel2, $row);
	}


	/**
	 * @return string
	 */
	public static function calculateHash($username, $password)
	{
    return sha1(strtoupper($username) . ":" . strtoupper($password));
	}

}
