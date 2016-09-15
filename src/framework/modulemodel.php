<?php
/*
 * Common functionalities for each module models
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/13
 */

namespace NsC3Framework;

class ModuleModel {
	
	/*
	* Database connection's informations
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	protected $database;

	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param DatabaseConnection $db the database connection's informations
	 */
	public function __construct($db) {
		$this->database = $db;
	}
	
	/*
	 * execute all query in $queries and return if all went well
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param array[string] $queries the queries to execute
	 * @return boolean if all queries executed without errors
	 * @todo add try,catch and exceptions handling
	 */
	public function executeQueries($queries) {
		foreach ($queries as $query) {
			$hasQuerySucceeded = $this->executeQuery($query);
			if(!$hasQuerySucceeded)
				return false;
		}
		return true; //success
	}
	
	/*
	 * execute provided query return if all went well
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $query the query to execute
	 * @return boolean if the query executed without errors
	 * @todo add try,catch and exceptions handling
	 */
	public function executeQuery($query) {
		return $this->database->getDatabaseInstance()->Execute($query);
	}

}
