<?php
/*
 * Manage interactions between KeywordsController and the database of the shop
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/14
 */

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/../framework/modulemodel.php');

class KeywordsModel extends \NsC3Framework\ModuleModel {

	/*
	 * the constructor
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param DatabaseConnection $db the database instance used for each query
	 */
	public function __construct($db) {
		parent::__construct($db);
	}
	
	/*
	 * query and return the list of categories in this shop, except root (nothing to display)
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @return mixed[] the list of categories in this shop
	 */
	public function getCategories() {
		$sql = 'SELECT id_category FROM `' . $this->database->getDatabasePrefix() . 'category` WHERE active=1 AND id_parent > 0';
		return $this->database->getDatabaseInstance()->executeS($sql);
	}
	
	/*
	 * return the $maxTagPerCategory's most common product tags in given category
	 * 
	 * @author Schnepp David
	 * @since 2016/09/14
	 * @param int $id_lang the lang used
	 * @param int $id_category the category to use
	 * @param int $maxTagPerCategory the maximum of tags to return for this category
	 * @return mixed[] $maxTagPerCategory's most common product tags in the category
	 */
	public function getMostCommonProductTagsPerCategory($id_lang, $id_category, $maxTagPerCategory) {
		$sql = 'SELECT tag_name, nb_occurrence FROM `' . $this->database->getDatabasePrefix() . 'vc3keywords` WHERE id_lang = ' . $id_lang . ' AND id_category = ' . $id_category . ' LIMIT ' . $maxTagPerCategory;
		return $this->database->getDatabaseInstance()->executeS($sql);
	}

}
