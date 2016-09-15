<?php

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/../framework/modulemodel.php');

/*
 * manage all interactions between the module and the database
 */

// common module logic
class KeywordsModel extends \NsC3Framework\ModuleControllerModel {

	public function __construct($db) {
		parent::__construct($db);
	}
	
	public static function getCategories() {
		$sql = 'SELECT id_category FROM `' . static::$database->getDatabasePrefix() . 'category` WHERE active=1 AND id_parent > 0';
		return static::$this->database->getDatabaseSlavedInstance()->executeS($sql);
	}
	
	public static function getMostCommonProductTagsPerCategory($id_lang, $id_category, $maxTagPerCategory) {
		$sql = 'SELECT tag_name, nb_occurrence FROM `' . static::$database->getDatabasePrefix() . 'vc3keywords` WHERE id_lang = ' . $id_lang . ' AND id_category = ' . $id_category . ' LIMIT ' . (int) $maxTagPerCategory;
		return static::$database->getDatabaseSlavedInstance()->executeS($sql);
	}

}
