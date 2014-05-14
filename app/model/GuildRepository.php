<?php
/**
 * Created by PhpStorm.
 * User: Dejv
 * Date: 14.5.14
 * Time: 12:03
 */

namespace AMS;
use Nette;


class GuildRepository extends CharacterRepository {


	public function __construct (Nette\Database\Connection $database)
	{
		parent::__construct($database);
	}

	public function findAll ()
	{
		$query = "SELECT cechy.id, cechy.name, COALESCE(character_extended_data.name, characters.name) AS guild_master
							FROM cechy
							LEFT JOIN cechy_clenove
								ON (cechy.id = cechy_clenove.id_spolku AND rank = 0)
							LEFT JOIN characters
								ON cechy_clenove.guid = characters.guid
							LEFT JOIN character_extended_data
								ON characters.guid = character_extended_data.guid";
		return $this->database->query ($query);
	}

	public function findMembers($guildId)
	{
		$query = "SELECT cechy.name, COALESCE(character_extended_data.name,characters.name) AS member, characters.deleteInfos_Name, cechy_ranky.name AS rank
							FROM cechy
							LEFT JOIN cechy_clenove
								ON cechy.id = cechy_clenove.id_spolku
							LEFT JOIN cechy_ranky
								ON (cechy_clenove.rank = cechy_ranky.id_ranku AND cechy_ranky.id_cechu = {$guildId})
							LEFT JOIN characters
								ON cechy_clenove.guid = characters.guid
							LEFT JOIN character_extended_data
								ON characters.guid = character_extended_data.guid
							WHERE cechy.id = {$guildId}
							ORDER BY cechy_ranky.id_ranku, COALESCE(character_extended_data.name,characters.name)";
		return $this->database->query($query);
	}
} 