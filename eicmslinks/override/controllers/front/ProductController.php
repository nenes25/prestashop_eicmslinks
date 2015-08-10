<?php

/**
 * Module Cms Link
 * Mise en place de liens dynamiques vers les pages cms
 * © h-hennes 2013-2015 
 * http://www.h-hennes.fr/blog/
 */
class ProductController extends ProductControllerCore
{

    public function initContent()
    {
        parent::initContent();

        //Module EiCmsLinks : On récupère les liens dynamiques des produits
        include_once(dirname(__FILE__).'/../../../modules/eicmslinks/eicmslinks.php');
        
        $this->product->description_short = EiCmsLinks::updateCmsLinksDisplay($this->product->description_short);
        $this->product->description = EiCmsLinks::updateCmsLinksDisplay($this->product->description);

        $this->context->smarty->assign(array('product' => $this->product));
    }

}
