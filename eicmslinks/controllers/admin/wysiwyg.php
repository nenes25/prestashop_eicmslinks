<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wysiwyg
 *
 * @author herve
 */
class WysiwygController extends ModuleAdminController {
    
    public function __construct() {
        
        parent::__construct();
    }
    
    /**
     * Listing ajax des catégories
     */
    public function displayAjaxCategoriesList() {
		ini_set('display_errors','on');
        $categoryTree = new HelperTreeCategories('categories-tree', $this->l('Check the category to display the link'));
		echo $categoryTree->setAttribute()
			   ->setInputName('id-category-for-insert')
			   ->render();
    }
    
    /**
     * Listing ajax des produits
     */
    public function displayAjaxProductsLists(){
        
    }
    
}
