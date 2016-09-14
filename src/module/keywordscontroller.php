<?php

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/modulemodel.php');
include_once(dirname(__FILE__) . '/../framework/modulecontroller.php');
/*
 * Basic c3 module logic 
 */

// common module logic
class KeywordsController extends \NsC3Framework\ModuleController {

	public function __construct($infos, $databaseConnection) {
		parent::__construct($infos, $databaseConnection);
	}
	
	public function getCachedTagsList($id_category) {
		$id = 'c3keywords_' . $id_category;
		$file = $id . '.cache';
		$path = static::$moduleInformations->getModuleCacheFilePath($file);
		return tim(ModuleIO::getFileContentToString($path));
	}

}
