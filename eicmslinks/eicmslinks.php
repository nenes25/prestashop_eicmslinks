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
class EiCmsLinks extends Module {

    private $html;

    public function __construct() {
        $this->name = 'eicmslinks';
        $this->tab = 'hhennes';
        $this->author = 'hhennes';
        $this->version = '2.0.0';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Ei Cms Links');
        $this->description = $this->l('Add Cms Links tools in tinyMce Editor');
    }

    public function install() {
        if (!parent::install() 
                //Hooks Backoffice
                || !$this->registerHook('actionAdminControllerSetMedia')
                //Hooks Fronts d'affichage
                || !$this->registerHook('filterCmsContent') 
                || !$this->registerHook('filterCmsCategoryContent') 
                || !$this->registerHook('filterProductContent') 
                || !$this->registerHook('filterCategoryContent') 
                || !$this->registerHook('filterManufacturerContent') 
                || !$this->registerHook('filterSupplierContent')
        )
            return false;


        $tab = new Tab();
        $tab->class_name = 'wysiwyg';
        //On va la ranger dans "Préférences comme les pages cms y sont insérée
        $id_parent = Tab::getIdFromClassName('AdminParentPreferences');
        $tab->id_parent = $id_parent;
        $tab->module = $this->name;
        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            $tab->name[$lang['id_lang']] = 'EiCmsLinks';
        }
        try {
            $tab->save();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        return true;
    }

    public function uninstall() {

        if (!parent::uninstall())
            return false;

        //Suppression de la tab admin
        $id_tab = Tab::getIdFromClassName('wysiwyg');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }

        return true;
    }

    public function hookFilterCmsContent($params) {

        $params['object']['content'] = $this->_updateCmsLinks($params['object']['content']);

        return [
            'object' => $params['object']
        ];
    }

    public function hookFilterCmsCategoryContent($params) {

        $params['object']['description'] = $this->_updateCmsLinks($params['object']['description']);

        return [
            'object' => $params['object']
        ];
    }

    public function hookFilterProductContent($params) {
        
    }

    public function hookFilterCategoryContent($params) {

        $params['object']['description'] = $this->_updateCmsLinks($params['object']['description']);

        return [
            'object' => $params['object']
        ];
    }

    public function hookFilterManufacturerContent($params) {

        return $this->_updateCmsLinks($params['filtered_content']);
    }

    public function hookFilterSupplierContent($params) {
        $params['object']['description'] = $this->_updateCmsLinks($params['object']['description']);

        return [
            'object' => $params['object']
        ];
    }

    /**
     * Mise à jour de l'objet cms pour remplacer les variables d'url des lien
     * @param string : contenu ou il faut remplacer les liens
     * @return string : contenu avec les liens remplacés
     */
    protected function _updateCmsLinks($content) {

        $content = urldecode($content);
        $link_model = $this->context->link;

        //Mise à jour des liens vers les pages cms
        preg_match_all('#{{cms id=([0-9+])}}#', $content, $cms_links);

        if (isset($cms_links[1]) && sizeof($cms_links[1])) {
            foreach ($cms_links[1] as $link) {
                $link_url = $link_model->getCMSLink($link);
                //$link_url = EiCmsLinks::removeBaseUrl($link_url);
                $content = preg_replace('#{{cms id=' . $link . '}}#', $link_url, $content);
            }
        }

        //Mise à jour des liens vers les pages categories
        preg_match_all('#{{category id=([0-9+])}}#', $content, $category_links);

        if (isset($category_links[1]) && sizeof($category_links[1])) {
            foreach ($category_links[1] as $category_link) {
                $category_link_url = $link_model->getCategoryLink($category_link);
                //$category_link_url = EiCmsLinks::removeBaseUrl($category_link_url);
                $content = preg_replace('#{{category id=' . $category_link . '}}#', $category_link_url, $content);
            }
        }

        //Mise à jour des liens vers les pages produits
        preg_match_all('#{{product id=([0-9+])}}#', $content, $product_links);

        if (isset($product_links[1]) && sizeof($product_links[1])) {
            foreach ($product_links[1] as $product_link) {
                $product_link_url = $link_model->getProductLink($product_link);
                //$product_link_url = EiCmsLinks::removeBaseUrl($product_link_url);
                $content = preg_replace('#{{product id=' . $product_link . '}}#', $product_link_url, $content);
            }
        }

        //Mise à jour des liens d'ajout au panier
        preg_match_all('#{{cart id=([0-9+])}}#', $content, $product_links);

        if (isset($product_links[1]) && sizeof($product_links[1])) {
            foreach ($product_links[1] as $product_link) {
                $product_cart_url = $link_model->getAddToCartURL($product_link, 0);
                //$product_cart_url = EiCmsLinks::removeBaseUrl($product_cart_url);
                $content = preg_replace('#{{cart id=' . $product_link . '}}#', $product_cart_url, $content);
            }
        }

        return $content;
    }

    
    public function hookActionAdminControllerSetMedia($params)
    {
        
       //@ToDO : gérer les controlleurs ou on souhaite l'afficher !
        $this->context->controller->addJS($this->_path.'/views/js/admin/widget.js');
    }
}
