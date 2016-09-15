<?php

namespace NsC3Framework;

include_once(dirname(__FILE__) . '/moduleio.php');
/*
 * Basic c3 module logic 
 */

// common module logic
abstract class ModuleController {
	protected static $model = null;
	protected static $isInitialized = false;
	protected static $moduleInformations = null;

	public function __construct($infos) {
		if(!static::$isInitialized){
			static::$moduleInformations = $infos;
			static::$isInitialized = true;
		}
	}
	
	public static function installModuleInDatabase() {
		$file = static::$moduleInformations->getModuleInstallationSqlFile();
		return static::convertFileContentToQueriesAndExecute($file);
	}
	
	public static function uninstallModuleInDatabase() {
		$file = static::$moduleInformations->getModuleUninstallationSqlFile();
		return static::convertFileContentToQueriesAndExecute($file);
	}
	
	protected static function convertFileContentToQueriesAndExecute($file) {
		$queries = static::convertFileContentToQueries($file);
		if(!$queries)
			return false;
		return static::$model->executeQueries($queries);
	}
	
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
	
	protected static function convertRawTextToSqlText($rawSql) {
		$sql = str_replace('PREFIX_', static::$moduleInformations->getPrestashopPrefix(), $rawSql);
		$sqlr = str_replace("\r", '', $sql);
		$res = str_replace("\n", '', $sqlr);
		return $res;
	}
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
	
	public static function installModuleCache() {
		if(static::isModuleCacheCreated())
			return true;
		$dir = static::$moduleInformations->getModuleCachePath();
		static::$isCacheCreated = ModuleIO::createDirectory($dir);
	}
	
	public static function uninstallModuleCache() {
		if(static::isModuleCacheCreated()) {
			$dir = static::$moduleInformations->getModuleCachePath();
			ModuleIO::emptyAndDeleteDirectory($dir);
		}
		return true;
	}
	
	public static function isModuleCacheCreated() {
		$dir = static::$moduleInformations->getModuleCachePath();
		return ModuleIO::existDirectory($dir);
	}
	

}
