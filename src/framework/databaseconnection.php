<?php

namespace NsC3Framework;

class DatabaseConnection {
	private $prestashopDatabaseInstance;
	private $prestashopPrefix;
	
	public function __construct($db, $prefix) {
		$this->prestashopDatabaseInstance = $db;
		$this->prestashopPrefix = $prefix;
	}
	
	public function getDatabaseInstance() {
		return $this->prestashopDatabaseInstance;
	}
	
	public function getDatabaseSlavedInstance() {
		return $this->prestashopDatabaseInstance;
	}
	
	public function getDatabasePrefix() {
		return $this->prestashopPrefix;
	}
}