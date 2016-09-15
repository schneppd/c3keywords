<?php

namespace NsC3Framework;


/*
 * manage all interactions between the module and the database
 */

// common module logic
class ModuleModel {
	protected $database;

	public function __construct($db) {
		$this->database = $db;
	}
	public function executeQueries($queries) {
		foreach ($queries as $query) {
			$hasQuerySucceeded = $this->executeQuery($query);
			if(!$hasQuerySucceeded)
				return false;
		}
		return true; //success
	}
	
	public function executeQuery($query) {
		return $this->database->getDatabaseInstance()->Execute($query);
	}

}
