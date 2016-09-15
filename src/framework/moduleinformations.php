<?php
/*
 * Stores informations about the module execution context
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/13
 */

namespace NsC3Framework;

class ModuleInformations {
	
	/*
	* The module name
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $moduleName;
	/*
	* The module absolute path
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $modulePath;
	/*
	* Prestashop's absolute cache path
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $prestashopCachePath;
	/*
	* Prestashop's sql prefix
	* 
	* @author Schnepp David
	* @since 2016/09/13
	*/
	private $prestashopPrefix;
	
	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $name the current module's name
	 * @param string $path absolute path to current module directory
	 * @param string $cache absolute path to prestashop cache directory
	 * @param string $prefix prestashop's sql prefix
	 */
	public function __construct($name, $path, $cache, $prefix) {
		$this->moduleName = $name;
		$this->modulePath = $path;
		$this->prestashopCachePath = $cache;
		$this->prestashopPrefix = $prefix;
	}
	
	/*
	 * accessor to $moduleName
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string the current module's name
	 */
	public function getModuleName(){
		return $this->moduleName;
	}
	
	/*
	 * accessor to $modulePath
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string absolute path to current module directory
	 */
	public function getModulePath(){
		return $this->modulePath;
	}
	
	/*
	 * accessor to $prestashopCachePath
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string absolute path to prestashop cache directory
	 */
	public function getPrestashopCachePath(){
		return $this->prestashopCachePath;
	}
	
	/*
	 * accessor to $prestashopPrefix
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string prestashop's sql prefix
	 */
	public function getPrestashopPrefix(){
		return $this->prestashopPrefix;
	}
	
	/*
	 * accessor to the module's cache path
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string module's cache path
	 */
	public function getModuleCachePath(){
		return $this->prestashopCachePath.$this->moduleName. '-cache';
	}
	
	/*
	 * accessor to the module's installation sql file
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string installation file path
	 */
	public function getModuleInstallationSqlFile() {
		return $this->modulePath.'/sql/install.sql';
	}
	
	/*
	 * accessor to the module's uninstallation sql file
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return string uninstallation file path
	 */
	public function getModuleUninstallationSqlFile() {
		return $this->modulePath.'/sql/uninstall.sql';
	}
	
	/*
	 * returns the absolute path to the file in the module's cache
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $file name of the file in the module's cache
	 * @return string file's absolute path
	 */
	public function getModuleCacheFilePath($file) {
		return $this->getModuleCachePath().'/'.$file;
	}
}