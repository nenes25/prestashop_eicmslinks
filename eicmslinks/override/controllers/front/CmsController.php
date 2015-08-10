<?php

/**
 * Module Cms Link
 * Mise en place de liens dynamiques vers les pages cms
 * © h-hennes 2013-2015 
 * http://www.h-hennes.fr/blog/
 */
class CmsController extends CmsControllerCore
{

	public function init()
	{
		parent::init();

		//Inclusion du module
		include_once(dirname(__FILE__).'/../../../modules/eicmslinks/eicmslinks.php');
		//Mise à jour des données des liens
		$this->cms->content = EiCmsLinks::updateCmsLinksDisplay($this->cms->content);
	}

}
