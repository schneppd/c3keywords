<?php
namespace NsC3Keywords;

if (!defined('_PS_VERSION_'))
    exit;

include_once(dirname(__FILE__).'/../../config/config.inc.php');
/*
 * Basic c3 module logic 
*/




// common module logic
class C3Module
{

    /*
    * read the content of given file and executes it, !!! always put this file in root directory
    * return true if no error, or else if something went wrong
    */
    public static function executeSqlFile($db, $file)
    {
        //the file is always in the module's root dir
        $sql_file_path = dirname(__FILE__) . '/sql/' . $file . '.sql';
        if (!file_exists($sql_file_path))
            return false;//abort
        //get file content in $sql
        else if (!$sql = file_get_contents($sql_file_path))
            return false;//abort
        //replace dummy PREFIX_ with prestashop's prefix value
        $sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
        $sql = str_replace("\r", '', $sql);
        $sql = str_replace("\n", '', $sql);
        //splite sql commands on each ";"
        //$sql = preg_split("/;\s*[\r\n]+/", $sql);
        $sql = explode("/;", $sql);
        foreach ($sql as $query){
            error_log("sql query: |$query|");
            if($sql != ''){
                if (!$db->Execute(trim($query)))
                    return false;//abort if sql error
            }
        }
        return true;//success
    }

    /*
    * return module's cache path
    * return true if no error, or else if something went wrong
    */
    public static function getModuleCachePath($moduleName)
    {
        //create the directory
        return _PS_CACHE_DIR_ . $moduleName . '-cache';
    }

    /*
    * return module's cache file path
    * return true if no error, or else if something went wrong
    */
    public static function getModuleCacheFilePath($moduleName, $file)
    {
        //create the directory
        return self::getModuleCachePath($moduleName) . '/' . $file;
    }

    /*
    * create a directory in prestashop's cache dir to store the module's files
    * return true if no error, or else if something went wrong
    */
    public static function createModuleCacheDir($moduleName)
    {
        $dir = self::getModuleCachePath($moduleName);
	//if dir already exists, do nothing and stop with res = validated
	if (file_exists($dir))
		return true;
        //else, create the directory
        if (!mkdir($dir, 0755, false))
            return false;
        return true;//success
    }

    /*
    * remove the module's cache directory
    * return true if no error, or else if something went wrong
    */
    public static function removeModuleCacheDir($moduleName)
    {
        // delete custom cache dir
        $dir = self::getModuleCachePath($moduleName);
        if (is_dir($dir)){
            //empty dir of content
                $objects = scandir($dir);
                foreach ($objects as $object)
                if (filetype($dir."/".$object) == "file")
                        self::deleteFile($dir."/".$object);
            reset($objects);
        }
        //delete dir
        rmdir($dir);
        return true;//success
    }

    /*
    * delete given file if exists
    */
    public static function deleteFile($file)
    {
        // delete file if exists
	if(file_exists($file))
		unlink($file);
    }

    /*
    * write a string to a file in module's cache
    * return true if no error, or else if something went wrong
    */
    public static function writeStringToModuleCache($moduleName, $file, $str)
    {
    $cacheFilePath = self::getModuleCachePath($moduleName) . '/' . $file;
    $wCacheFile = fopen($cacheFilePath, "w") or die("Unable to open cache!");
    fwrite($wCacheFile, $str);
    fclose($wCacheFile);
        return true;//success
    }


}

