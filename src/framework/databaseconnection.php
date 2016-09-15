<?php
/*
 * Stores information on current shop database connection
 * and provide centralized accessibility 
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/13
 */

namespace NsC3Framework;

class DatabaseConnection {
	
	/*
	* Database instance fro the module queries
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $prestashopDatabaseInstance;
	
	/*
	* Prestashop prefix for each table
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $prestashopPrefix;
	
	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param object $db the database instance used for each query
	 * @param string $prefix the value of prestashop _DB_PREFIX_
	 */
	public function __construct($db, $prefix) {
		$this->prestashopDatabaseInstance = $db;
		$this->prestashopPrefix = $prefix;
	}
	
	/*
	 * return the database instance in $prestashopDatabaseInstance
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @return object the database instance 
	 */
	public function getDatabaseInstance() {
		return $this->prestashopDatabaseInstance;
	}
	
	/*
	 * return the value of $prestashopPrefix
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @return string the defined prestashop prefix
	 */
	public function getDatabasePrefix() {
		return $this->prestashopPrefix;
	}
}