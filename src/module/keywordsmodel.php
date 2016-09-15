<?php

namespace NsC3KeywordsModule;

include_once(dirname(__FILE__) . '/../framework/modulemodel.php');

/*
 * manage all interactions between the module and the database
 */

// common module logic
class KeywordsModel extends \NsC3Framework\ModuleModel {

	public function __construct($db) {
		parent::__construct($db);
	}
	
	public function getCategories() {
		$sql = 'SELECT id_category FROM `' . $this->database->getDatabasePrefix() . 'category` WHERE active=1 AND id_parent > 0';
		return $this->database->getDatabaseInstance()->executeS($sql);
	}
	
	public function getMostCommonProductTagsPerCategory($id_lang, $id_category, $maxTagPerCategory) {
		$sql = 'SELECT tag_name, nb_occurrence FROM `' . $this->database->getDatabasePrefix() . 'vc3keywords` WHERE id_lang = ' . $id_lang . ' AND id_category = ' . $id_category . ' LIMIT ' . $maxTagPerCategory;
		return $this->database->getDatabaseInstance()->executeS($sql);
	}

}
