<?php
/*
 * Process inputs from module view(c3keywords),
 * tells the model what to save
 * tells the view what to display/expose to prestashop
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/14
 */

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/keywordsmodel.php');
include_once(dirname(__FILE__) . '/../framework/modulecontroller.php');
include_once(dirname(__FILE__) . '/../framework/moduleio.php');

class KeywordsController extends \NsC3Framework\ModuleController {

	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param ModuleInformations $infos the informations for this module
	 * @param DatabaseConnection $databaseConnection the database connection to use for the models
	 */
	public function __construct($infos, $databaseConnection) {
		parent::__construct($infos);
		static::$model = new KeywordsModel($databaseConnection);
	}
	
	/*
	 * Reads the content of the provided category's cache file and returns it as a valid html string for prestashop frontend display
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param int $id_category the category from which cache file should be read
	 * @return string the content of the cache file (should be html)
	 */
	public function getCachedTagsListHtml(&$id_category) {
		$file = 'c3keywords_' . $id_category .'.cache';
		$path = static::$moduleInformations->getModuleCacheFilePath($file);
		$rawHtml = \NsC3Framework\ModuleIO::getFileContentToString($path);
		$html = trim($rawHtml);
		return $html;
	}
	
	/*
	 * Returns each catagory and there $maxTagPerCategory's most common product tags
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param int $id_lang the shop current lang
	 * @param int $maxTagPerCategory the maximum tags per category
	 * @return mixed[cacheid => tags] the dictionary on each category and the most common product tags
	 */
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
	
	/*
	 * Delete and recreate the category's cache file
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param string $cacheId the cacheId part of the category cache file's name
	 * @param string $html the content (list of product tags processed with template/front/c3keywords) to save in the new cache file
	 */
	public function regenerateTagListCache(&$cacheId, &$html) {
		$cacheFileName = $cacheId . '.cache';
		$cacheFile = static::$moduleInformations->getModuleCacheFilePath($cacheFileName);
		\NsC3Framework\ModuleIO::safeDeleteFile($cacheFile);
		\NsC3Framework\ModuleIO::writeStringToFile($html, $cacheFile);
	}
	
	/*
	 * Returns if provided category's cache file exists
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param int $id_category the category to check cache file's existence
	 * @return bool if the category's cache file exists
	 */
	public function canDisplayTagList(&$id_category) {
		$file = 'c3keywords_' . $id_category. '.cache';
		$filePath = static::$moduleInformations->getModuleCacheFilePath($file);
		return \NsC3Framework\ModuleIO::existFile($filePath);
	}

}
