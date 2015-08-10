<?php

/**
 * Module Cms Link
 * Mise en place de liens dynamiques vers les pages cms
 * © h-hennes 2013-2015 
 * http://www.h-hennes.fr/blog/
 */
class CategoryController extends CategoryControllerCore
{

    public function initContent()
    {
        parent::initContent();

        include_once(dirname(__FILE__).'/../../../modules/eicmslinks/eicmslinks.php');
		//Module EiCmsLinks : On récupère les liens dynamiques des catégories
        $this->category->description = EiCmsLinks::updateCmsLinksDisplay($this->category->description);
        $this->context->smarty->assign(
            array(
                'description_short' => Tools::truncateString(EiCmsLinks::updateCmsLinksDisplay($this->category->description),350),
                'category' => $this->category,
            )
        );
    }
}
