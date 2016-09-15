<?php

namespace NsC3Framework;

class DatabaseConnection {
	private $prestashopDatabase;
	private $prestashopPrefix;
	private $isSqlSlave;
	
	public function __construct($db, $prefix, $isSlave) {
		$this->prestashopDatabase = $db;
		$this->prestashopPrefix = $prefix;
		$this->isSqlSlave = $isSlave;
	}
	
	public function getDatabaseInstance() {
		return $this->prestashopDatabase->getInstance();
	}
	
	public function getDatabaseSlavedInstance() {
		return $this->prestashopDatabase->getInstance($this->isSqlSlave);
	}
	
	public function getDatabasePrefix() {
		return $this->prestashopPrefix;
	}
}