<?php

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/keywordsmodel.php');
include_once(dirname(__FILE__) . '/../framework/modulecontroller.php');
/*
 * Basic c3 module logic 
 */

// common module logic
class KeywordsController extends \NsC3Framework\ModuleController {

	public function __construct($infos, $databaseConnection) {
		parent::__construct($infos);
		static::$model = new KeywordsModel($databaseConnection);
	}
	
	public function getCachedTagsList(&$id_category) {
		$id = 'c3keywords_' . $id_category;
		$file = $id . '.cache';
		$path = static::$moduleInformations->getModuleCacheFilePath($file);
		$html = tim(ModuleIO::getFileContentToString($path));
		return $html;
	}
	
	public function getProductTagsPerCategoryList($id_lang, $maxTagPerCategory) {
		$caches = [];
		$categories = static::$model->getCategories();
		foreach ($categories as $category) {
			$id_category = (int) $category['id_category'];
			$cacheId = 'c3keywords_' . $id_category;
			$tags = static::$model->getMostCommonProductTagsPerCategory($id_lang, $id_category, $maxTagPerCategory);
			$caches[$cacheId] = $tags;
		}
		return $caches;
	}
	
	public function regenTagListCache(&$cacheId, &$html) {
		$cacheFileName = $cacheId . '.cache';
		$cacheFile = static::$moduleInformations->getModuleCacheFilePath($cacheFileName);
		ModuleIO::safeDeleteFile($cacheFile);
		ModuleIO::writeStringToFile($html, $cacheFile);
	}

}
