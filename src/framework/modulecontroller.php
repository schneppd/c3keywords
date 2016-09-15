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
	protected static $isCacheCreated = false;

	public function __construct($infos) {
		if(!static::$isInitialized){
			static::$moduleInformations = $infos;
			static::$isInitialized = true;
		}
	}
	
	public static function installModuleInDatabase() {
		$file = static::$moduleInformations->getModuleInstallationSqlFile();
		return static::executeFileQueries($file);
	}
	
	public static function uninstallModuleInDatabase() {
		$file = static::$moduleInformations->getModuleUninstallationSqlFile();
		return static::executeFileQueries($file);
	}
	
	public static function executeFileQueries($file) {
		if(!ModuleIO::existFile($file))
			return false;
		$rawSql = ModuleIO::getFileContentToString($file);
		$sql = static::convertRawTextToSqlText($rawSql);
		if(!$sql)
			return false;
		$queries = static::splitSqlTextInQueries($sql);
		return static::$model->executeQueries($queries);
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
		$dir = static::$moduleInformations->getModuleCachePath();
		if(ModuleIO::existDirectory($dir))
			return true;
		static::$isCacheCreated = ModuleIO::createDirectory($dir);
		return static::$isCacheCreated;
	}
	
	public static function uninstallModuleCache() {
		$dir = static::$moduleInformations->getModuleCachePath();
		if(ModuleIO::existDirectory($dir))
			ModuleIO::emptyAndDeleteDirectory($dir);
		static::$isCacheCreated = false;
		return true;
	}
	
	public static function isModuleCacheCreated() {
		return static::$isCacheCreated;
	}
	
	/*
	 * write a string to a file in module's cache
	 * return true if no error, or else if something went wrong
	 */
	public static function writeStringToModuleCache($moduleName, $file, $str) {
		$cacheFilePath = self::getModuleCachePath($moduleName) . '/' . $file;
		$wCacheFile = fopen($cacheFilePath, "w") or die("Unable to open cache!");
		fwrite($wCacheFile, $str);
		fclose($wCacheFile);
		return true; //success
	}

}
