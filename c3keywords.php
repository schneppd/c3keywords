<?php
/*
 * This module is used to show the most common keywords between each products per category as a list in the front-end's left column
 * @param int C3KEYWORDS_NB the max number of keywords to show per category
 */

// if major problem with prestashop, abort
if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__) . '/src/module/keywordscontroller.php');
require_once(dirname(__FILE__) . '/src/framework/databaseconnection.php');
require_once(dirname(__FILE__) . '/src/framework/moduleinformations.php');

class C3Keywords extends Module {

	protected $controller;
	
	function __construct() {
		//setup this module's basic informations
		$this->name = 'c3keywords';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Schnepp David';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		//setup this module's informations for back-end
		$this->displayName = $this->l('C3Keywords block');
		$this->description = $this->l("Adds C3's list of most common product tags per category in front-end.");
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		
		$this->customizeModuleConstruction();

	}
	
	protected function customizeModuleConstruction() {
		$moduleInformations = new \NsC3Framework\ModuleInformations($this->name, dirname(__FILE__), _PS_CACHE_DIR_, _DB_PREFIX_);
		$dbConnection = new \NsC3Framework\DatabaseConnection(Db, _DB_PREFIX_, _PS_USE_SQL_SLAVE_);
		$this->controller = new \NsC3KeywordsModule\KeywordsController($moduleInformations, $dbConnection);
	}
	
	/*
	 * steps to execute when the module is installed
	 * @return bool if the installation succeed
	 */
	function install() {
		if(!$this->controller->installModuleInDatabase())
			return false;
		if(!$this->controller->installModuleCache())
			return false;

		// clear cache to delete possible afterfacts
		$this->_clearCache('*');

		//register module in hooks
		if (!parent::install() ||
				 // register module for following hooks
				 !$this->registerHook('header') ||
				 !$this->registerHook('leftColumn') ||
				 !$this->registerHook('addproduct') ||
				 !$this->registerHook('updateproduct') ||
				 !$this->registerHook('deleteproduct') ||
				 // max tags to display
				 !Configuration::updateValue('C3KEYWORDS_NB', 9)
		)
			return false;
		
		return true;
	}

	/*
	 * steps to execute when the module is uninstalled
	 * @return bool if the uninstallation succeed
	 */
	public function uninstall() {
		// clear cache to delete possible afterfacts
		$this->_clearCache('*');

		if(!$this->controller->uninstallModuleInDatabase())
			return false;
		if(!$this->controller->uninstallModuleCache())
			return false;

		// uninstall module from hooks
		if (!parent::uninstall() || !Configuration::deleteByName('C3KEYWORDS_NB'))
			return false;

		return true;
	}

	/*
	 * clear cached data in template
	 */
	protected function _clearCache($template, $cache_id = NULL, $compile_id = NULL) {
		parent::_clearCache('c3keywords.tpl');
	}

	/*
	 * clear cached data in template if a product is added in shop
	 */
	public function hookAddProduct($params) {
		$this->_clearCache('c3keywords.tpl');
	}

	/*
	 * clear cached data in template if a product is updated in shop
	 */
	public function hookUpdateProduct($params) {
		$this->_clearCache('c3keywords.tpl');
	}

	/*
	 * clear cached data in template if a product is deleted in shop
	 */
	public function hookDeleteProduct($params) {
		$this->_clearCache('c3keywords.tpl');
	}

	/*
	 * add module css to head hook
	 */
	public function hookHeader($params) {
		$this->context->controller->addCSS(($this->_path) . 'views/css/c3keywords.css', 'all');
	}

	/*
	 * add module css to head hook
	 * @return bool if the process succeed
	 */
	public function hookLeftColumn($params) {
		// get current id_category
		$id_category = (int) (Tools::getValue('id_category'));
		if ($id_category > 0) {
			return $this->controller->getCachedTagsListHtml($id_category);
		}
	}

	/*
	 * redirect right column to left
	 */
	public function hookRightColumn($params) {
		return $this->hookLeftColumn($params);
	}

	/*
	 * process backend form post for module
	 * @return string html response
	 */
	public function getContent() {
		$output = null;
		$errors = array();
		//if correct sending
		if (Tools::isSubmit('submit'.$this->name)) {
			//check if module's cache dir exists
			$isCacheExist = $this->controller->isModuleCacheCreated();
			if (!$isCacheExist)
				$errors[] = $this->l('There is an error with the module\'s cache dir creation/existence (rights problem most likely).');
			// check if C3KEYWORDS_NB was provided
			$maxTagPerCategory = Tools::getValue('C3KEYWORDS_NB');
			if (!strlen($maxTagPerCategory))
				$errors[] = $this->l('Please complete the "Displayed tags" field.');
			elseif (!Validate::isInt($maxTagPerCategory) || (int) ($maxTagPerCategory) <= 0)
				$errors[] = $this->l('Invalid number.');
			// if errors, display error messages
			if (count($errors))
				$output = $this->displayError(implode('<br />', $errors));
			else {
				// update module values
				Configuration::updateValue('C3KEYWORDS_NB', (int) $maxTagPerCategory);
				$this->regenerateTagsListsCaches();
				
				$output = $this->displayConfirmation($this->l('Tagblocks generated'));
			}
		}
		return $output . $this->renderForm();
	}
	
	protected function regenerateTagsListsCaches() {
		$maxTagPerCategory = (int)Tools::getValue('C3KEYWORDS_NB');
		$id_lang = (int) $this->context->language->id;
		$tagsLists = $this->controller->getProductTagsPerCategoryList($id_lang, $maxTagPerCategory);
		$tagsListsWithLinks = $this->addPrestashopTagLinkToTags($tagsLists);
		$this->cacheTagsLists($tagsListsWithLinks);
	}
	
	protected function addPrestashopTagLinkToTags($tagList) {
		foreach ($tagsLists as $cacheId => $tags) {
			if(!count($tags)) {
				//this category don't have any tags / no products
				continue;
			} else {
				for ($i = 0; $i < count($tags); $i++) {
					$tags[$i]['link'] = $this->context->link->getPageLink('search', true, NULL, 'tag=' . urlencode($tags[$i]['tag_name']));
				}
			}
		}
		return $tagsLists;
	}
	
	protected function cacheTagsLists($tagsLists) {
		foreach ($tagsLists as $cacheId => $tags) {
			$html = $this->convertTagListToHtml($cacheId, $tags);
			$this->controller->regenerateTagListCache($cacheId, $html);
		}
	}
	
	protected function convertTagListToHtml(&$cacheId, &$tags) {
		$this->smarty->assign(array('tags' => $tags));
		$html = $this->display(__FILE__, 'views/templates/front/c3keywords.tpl', $cacheId);
		return $html;
	}
	
	// backend form creation
	public function renderForm() {
		// setup form fields
		$fields_form = array(
			 'form' => array(
				  'legend' => array(
						'title' => $this->l('Settings'),
						'icon' => 'icon-cogs'
				  ),
				  'input' => array(
						array(
							 'type' => 'text',
							 'label' => $this->l('Displayed tags'),
							 'name' => 'C3KEYWORDS_NB',
							 'class' => 'fixed-width-xs',
							 'desc' => $this->l('Set number of keywords you would like to displayed per page. (default: 9)')
						)
				  ),
				  'submit' => array(
						'title' => $this->l('Generate Tagblocks'),
				  )
			 ),
		);
		// setup form infos
		$helper = new HelperForm();
		// Module logic, token and currentIndex
		$helper->module = $this;
		$helper->table = $this->table;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&c3keywords_module=' . $this->tab . '&module_name=' . $this->name;
		// toolbar logic
		$helper->show_toolbar = false;
		// module langue
		$default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		// submit logic
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit' . $this->name;


		$helper->tpl_vars = array(
			 'fields_value' => $this->getConfigFieldsValues(),
			 'languages' => $this->context->controller->getLanguages(),
			 'id_language' => $this->context->language->id
		);
		// generate form
		return $helper->generateForm(array($fields_form));
	}

	// output config fields to array
	public function getConfigFieldsValues() {
		return array(
			 'C3KEYWORDS_NB' => Tools::getValue('C3KEYWORDS_NB', (int) Configuration::get('C3KEYWORDS_NB')),
		);
	}

}
