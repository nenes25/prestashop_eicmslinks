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
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */

class WidgetProducts extends Widget {
    
    public $template = 'products.tpl';
    protected static $_allowed_params = array('type','nb_products','id_category');
    
    /**
     * Préparation du contenu
     */
    public function initContent() {
        parent::initContent();

        //Définition du nombre de produits à afficher
        $this->_datas['nb_products'] ? $nb_products = (int) $this->_datas['nb_products'] : $nb_products = (int) Configuration::get('NEW_PRODUCTS_NBR');

        //Définition du type de produits à afficher ( New / Bestsellers / Price Drop )
        $this->_datas['type'] ? $type = $this->_datas['type'] : $type = 'new';

        //Assignation des produits
        $products = $this->_getProducts($type, $nb_products);
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('type',$type);
    }

    /**
     * Récupération de la liste des produits
     * @param string $type
     * @param int $nb_products
     */
    protected function _getProducts($type,$nb_products) {
        
        switch ( $type ) {
            
            case 'price_drop':
                return Product::getPricesDrop((int) $this->context->language->id,0, $nb_products);
            break;
        
            case 'category':
                $this->_datas['id_category'] ? $id_category = $this->_datas['id_category'] : $id_category = (int)Configuration::get('HOME_FEATURED_CAT');
                $category = new Category($id_category,(int)$this->context->language->id);
                return $category->getProducts((int)$this->context->language->id, 1, $nb_products, 'position');
            break;
            
            case 'new':
            default:
               return Product::getNewProducts((int) $this->context->language->id, 0, $nb_products);
            break;
        }
    }
}
