<?php
/*
 * Common functionalities for each module controller
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/13
 */

namespace NsC3Framework;

include_once(dirname(__FILE__) . '/moduleio.php');

abstract class ModuleController {
	
	/*
	* The controller's model
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	protected $model = null;
	/*
	* The controller's singleton for only one $moduleInformations
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	protected static $isInitialized = false;
	/*
	* Informations provided from the prestashop module about execution context
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	protected static $moduleInformations = null;

	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param ModuleInformations $infos informations about the module's execution context
	 */
	public function __construct($infos) {
		if(!static::$isInitialized){
			static::$moduleInformations = $infos;
			static::$isInitialized = true;
		}
	}
	
	/*
	 * read, process and execute the sql in module_dir/sql/install.sql
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return boolean if the installation succeeded
	 */
	public function installModuleInDatabase() {
		$file = static::$moduleInformations->getModuleInstallationSqlFile();
		return $this->convertFileContentToQueriesAndExecute($file);
	}
	
	/*
	 * read, process and execute the sql in module_dir/sql/uninstall.sql
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return boolean if the uninstallation succeeded
	 */
	public function uninstallModuleInDatabase() {
		$file = static::$moduleInformations->getModuleUninstallationSqlFile();
		return $this->convertFileContentToQueriesAndExecute($file);
	}
	
	/*
	 * reads content of provided text file to valid sql queries and executes them
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $file the file to read
	 * @return boolean if the uninstallation succeeded
	 */
	protected function convertFileContentToQueriesAndExecute($file) {
		$queries = static::convertFileContentToQueries($file);
		if(!$queries)
			return false;
		return $this->model->executeQueries($queries);
	}
	
	/*
	 * reads content of provided text file to valid sql queries
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $file the file to read
	 * @return array[string] | false the queries
	 */
	protected static function convertFileContentToQueries($file) {
		if(!ModuleIO::existFile($file))
			return false;
		$rawSql = ModuleIO::getFileContentToString($file);
		$sql = static::convertRawTextToSqlText($rawSql);
		if(!$sql)
			return false;
		$queries = static::splitSqlTextInQueries($sql);
		return $queries;
	}
	
	/*
	 * process the text from sql file to valide sql text
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $rawSql the raw text from an sql file
	 * @return string the corrected sql text
	 */
	protected static function convertRawTextToSqlText($rawSql) {
		$sql = str_replace('PREFIX_', static::$moduleInformations->getPrestashopPrefix(), $rawSql);
		$sqlr = str_replace("\r", '', $sql);
		$res = str_replace("\n", '', $sqlr);
		return $res;
	}

	/*
	 * separate provided sql text in queries
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $sql the sql text to split
	 * @return array[string] the queries
	 */
	protected static function splitSqlTextInQueries($sql) {
		$queries = [];
		$rawQueries = explode("/;", $sql);
		foreach($rawQueries as $rawQuery){
			if(!empty($rawQuery)) {
				$query = trim($rawQuery);
				array_push($queries, $query);
			}
		}
		return $queries;
	}
	
	/*
	 * create the module's directory cache in prestashop's cache
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return boolean if the directory exists or has been correctly created
	 */
	public static function installModuleCache() {
		if(static::isModuleCacheCreated())
			return true;
		$dir = static::$moduleInformations->getModuleCachePath();
		return ModuleIO::createDirectory($dir);
	}
	
	/*
	 * remove the module's directory cache in prestashop's cache
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return boolean if the directory don't exist or has been correctly deleted
	 */
	public static function uninstallModuleCache() {
		if(static::isModuleCacheCreated()) {
			$dir = static::$moduleInformations->getModuleCachePath();
			ModuleIO::emptyAndDeleteDirectory($dir);
		}
		return true;
	}
	
	/*
	 * tells if the module's directory cache exists in prestashop's cache
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return boolean if the directory exists
	 */
	public static function isModuleCacheCreated() {
		$dir = static::$moduleInformations->getModuleCachePath();
		return ModuleIO::existDirectory($dir);
	}
	

}
