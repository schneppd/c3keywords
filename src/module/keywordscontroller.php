<?php

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/keywordsmodel.php');
include_once(dirname(__FILE__) . '/../framework/modulecontroller.php');
include_once(dirname(__FILE__) . '/../framework/moduleio.php');
/*
 * Basic c3 module logic 
 */

// common module logic
class KeywordsController extends \NsC3Framework\ModuleController {

	public function __construct($infos, $databaseConnection) {
		parent::__construct($infos);
		static::$model = new KeywordsModel($databaseConnection);
	}
	
	public function getCachedTagsListHtml(&$id_category) {
		$file = 'c3keywords_' . $id_category .'.cache';
		$path = static::$moduleInformations->getModuleCacheFilePath($file);
		$rawHtml = \NsC3Framework\ModuleIO::getFileContentToString($path);
		$html = trim($rawHtml);
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
	
	public function regenerateTagListCache(&$cacheId, &$html) {
		$cacheFileName = $cacheId . '.cache';
		$cacheFile = static::$moduleInformations->getModuleCacheFilePath($cacheFileName);
		\NsC3Framework\ModuleIO::safeDeleteFile($cacheFile);
		\NsC3Framework\ModuleIO::writeStringToFile($html, $cacheFile);
	}
	
	public function canDisplayTagList(&$id_category) {
		$file = 'c3keywords_' . $id_category. '.cache';
		$filePath = static::$moduleInformations->getModuleCacheFilePath($file);
		return ModuleIO::existFile($filePath);
	}

}
