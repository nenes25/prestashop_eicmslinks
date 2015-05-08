<?php

/**
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2015 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */
class WysiwygController extends ModuleAdminController {

	/** Nom du helper de liste des produits */
	protected $helper_list_name = 'add_product_link_form';

    public function __construct() {

        parent::__construct();
    }

    /**
     * Listing ajax des catégories
     */
    public function displayAjaxCategoriesList() {
	
		//Insertion des styles admin nécessaire à l'affichage des actions ajax
        foreach ( $this->css_files as $css_key => $css_type ) {
            echo '<link rel="stylesheet" type="text/css" href="'.$css_key.'" type="'.$css_type.'"/>';
        }
        //Géneration du tree des catégories
        if (_PS_VERSION_ < '1.6') {
            $categoryTree = new Helper();
            echo $categoryTree->renderCategoryTree(2,array(),'id-category-for-insert');
        }
        else {
            $categoryTree = new HelperTreeCategories('categories-tree', $this->l('Check the category to display the link'));
            echo $categoryTree->setAttribute()
                    ->setInputName('id-category-for-insert')
                    ->render();
        }
    }

    /**
     * Listing ajax des produits
     */
    public function displayAjaxProductsList() {
	
		$filterCond = $this->_getProductFilterConditions();
	
		//Récupération des produits
		$products = Db::getInstance()->ExecuteS("SELECT p.id_product,p.reference, pl.name 
												FROM ps_product p
												LEFT JOIN ps_product_lang pl ON ( p.id_product = pl.id_product AND pl.id_lang = ".$this->context->language->id.")
												" .$filterCond);
		$fields_list = array(
						'id_product' => array(
							'title' => $this->l('id'),
							'type' => 'text',
							'width' => 50,
							'class' => 'product_id'
						),
						'reference' => array ( 
							'title' => $this->l('ref'),
							'type' => 'text',
							'width' => 100,
						),
						'name' => array ( 
							'title' => $this->l('name'),
							'type' => 'text',
							'width' => 150,
							'class' => 'product_name'
						),
					);
	
		$productList = new HelperList();
		$productList->simple_header = false;
		$productList->identifier = 'id_product';
		$productList->title = 'Product List';
		$productList->table = $this->helper_list_name;
		$productList->shopLinkType = '';
		$productList->currentIndex = str_replace('index.php','',$_SERVER['PHP_SELF']).$this->context->link->getAdminLink('Wysiwyg&module=eicmslinks&action=ProductsList&ajax=1');
		$productList->token = $this->token;
		$productList->no_link= true;
		
		echo $productList->generateList($products,$fields_list);
		
        
    }

	/**
	 * Construction de la requête de filtrage des produits
	 */
	protected function _getProductFilterConditions() {
	
		$conditions = array();
		$sql_conditions = '';
		
		if ( Tools::getValue($this->helper_list_name.'Filter_id_product') && Tools::getValue($this->helper_list_name.'Filter_id_product') != '' ) {
			$conditions[] = 'p.id_product = '.(int)Tools::getValue($this->helper_list_name.'Filter_id_product');
		}
		if ( Tools::getValue($this->helper_list_name.'Filter_reference') && Tools::getValue($this->helper_list_name.'Filter_reference') != '') {
			$conditions[] = "p.reference LIKE '".Tools::getValue($this->helper_list_name.'Filter_reference')."%'";
		}
		if ( Tools::getValue($this->helper_list_name.'Filter_name') && Tools::getValue($this->helper_list_name.'Filter_name') != '' ) {
			$conditions[] = "pl.name LIKE '".Tools::getValue($this->helper_list_name.'Filter_name')."%'";
		}
		
		for ( $i = 0 ; $i < sizeof($conditions) ; $i++ ) {
			if ( $i == 0 )
			 $sql_conditions .= ' WHERE '.$conditions[$i];
			else
				$sql_conditions .= ' AND '.$conditions[$i];
		}
		
		return $sql_conditions;
			
	}
}
