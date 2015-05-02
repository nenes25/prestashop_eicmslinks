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

    public function __construct() {

        parent::__construct();
    }

    /**
     * Listing ajax des catégories
     */
    public function displayAjaxCategoriesList() {
        
        //Insertion des styles admin nécessaire à l'affichage
        foreach ( $this->css_files as $css_key => $css_type ) {
            echo '<link rel="stylesheet" type="text/css" href="'.$css_key.'" type="'.$css_type.'"/>';
        }
        //Géneration du tree des catégories
        $categoryTree = new HelperTreeCategories('categories-tree', $this->l('Check the category to display the link'));
        echo $categoryTree->setAttribute()
                ->setInputName('id-category-for-insert')
                ->render();
    }

    /**
     * Listing ajax des produits
     */
    public function displayAjaxProductsLists() {
        
    }

}
