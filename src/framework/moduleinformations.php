<?php

namespace NsC3Framework;

class ModuleInformations {
	private $moduleName;
	private $modulePath;
	private $prestashopCachePath;
	private $prestashopPrefix;
	
	public function __construct($name, $path, $cache, $prefix) {
		$this->moduleName = $name;
		$this->modulePath = $path;
		$this->prestashopCachePath = $cache;
		$this->prestashopPrefix = $prefix;
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}
	
	public function getModulePath(){
		return $this->modulePath;
	}
	
	public function getPrestashopCachePath(){
		return $this->prestashopCachePath;
	}
	
	public function getPrestashopPrefix(){
		return $this->prestashopPrefix;
	}
	
	public function getModuleCachePath(){
		return $this->prestashopCachePath.$this->moduleName. '-cache';
	}
	public function getModuleInstallationSqlFile() {
		return $this->modulePath.'/sql/install.sql';
	}
	public function getModuleUninstallationSqlFile() {
		return $this->modulePath.'/sql/uninstall.sql';
	}
	public function getModuleCacheFilePath($file) {
		return $this->getModuleCachePath().'/'.$file;
	}
}