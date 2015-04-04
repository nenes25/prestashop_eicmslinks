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
class EiCmsLinks extends Module
{
	public function __construct()
	{
		$this->name = 'eicmslinks';
		$this->tab = 'hhennes';
		$this->author = 'hhennes';
		$this->version = '0.3.0';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Ei Cms Links');
		$this->description = $this->l('Add Cms Links tools in tinyMce Editor');
	}

	public function install()
	{
		if (!parent::install())
			return false;

		//@todo: Gestion des erreurs lors de la copie des fichiers
		//Copie des dossier de l'editeur tinyMce
		$this->copyDir(dirname(__FILE__).'/tiny_mce/', dirname(__FILE__).'/../../js/tiny_mce/plugins/');

		//Copie de l'override du formulaire cms de l'admin (Normalement devrait fonctionner via prestashop)
		$this->copyDir(dirname(__FILE__).'/override/controllers/admin/templates/', dirname(__FILE__).'/../../override/controllers/admin/templates/');
		
		//Spécifique 1.5 ( on renomme le fichier de surcharge avec le bon nom car ils ne sont pas compatibles entre les versions )
		if ( _PS_VERSION_ < '1.6' ) {
			rename(dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form.tpl',dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form16.tpl');
			rename(dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form15.tpl',dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form.tpl');
		}
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall())
			return false;

		//Suppression des fichiers lors de la désinstallation
		if ( !$this->deleteDir(dirname(__FILE__).'/../../js/tiny_mce/plugins/eicmslinks/') )
				return false;

		//Si la suppression du template admin si la desinstall prestashop n'a pas fonctionné on le supprime 
		if (is_file(dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form.tpl'))
			return unlink(dirname(__FILE__).'/../../override/controllers/admin/templates/cms/helpers/form/form.tpl');

		return true;

		return true;
	}

	/**
	 * Copie du contenu d'un dossier vers un autre emplacement
	 * @param string $dir2copy : Chemin du dossier à copier
	 * @param string $dir_paste : Chemin vers lequel copier le dossier
	 * @return void
	 */
	public function copyDir($dir2copy, $dir_paste)
	{
		// On vérifie si $dir2copy est un dossier
		if (is_dir($dir2copy))
		{
			// Si oui, on l'ouvre
			if ($dhd = opendir($dir2copy))
			{
				// On liste les dossiers et fichiers de $dir2copy
				while (($file = readdir($dhd)) !== false)
				{
					// Si le dossier dans lequel on veut coller n'existe pas, on le créé
					if (!is_dir($dir_paste))
						$create_dir = mkdir($dir_paste, 0777);

					// S'il s'agit d'un dossier, on relance la fonction récursive
					if (is_dir($dir2copy.$file) && $file != '..' && $file != '.')
						$this->copyDir($dir2copy.$file.'/', $dir_paste.$file.'/');
					// S'il sagit d'un fichier, on le copue simplement
					elseif ($file != '..' && $file != '.')
						$copy_file = copy($dir2copy.$file, $dir_paste.$file);
				}

				// On ferme $dir2copy
				closedir($dhd);
			}
		}
	}

	/**
	 * Supression récursive d'un dossier
	 * @param type $dir
	 * @return boolean
	 */
	public function deleteDir($dir)
	{
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file)
		{
			(is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
	/**
	 * Mise à jour de l'objet cms pour remplacer les variables d'url des lien
	 * @param Cms $cms : Object cms || null
	 * @return Cms $cms : Object cms avec valeur du contenu mis à jour
	 */
	public static function updateCmsLinksDisplay($cms = null)
	{
		
		if ($cms === null)
			return;
		
		//Dans prestashop 1.6 les caractères { et } sont encodés
		if ( _PS_VERSION_ > '1.6' )
			$cms->content = urldecode($cms->content);
		
		//On mets à jour le contenu en remplaçant les liens vers les pages
		preg_match_all('#{{cms url=([0-9])}}#', $cms->content, $links);

		if (isset($links[1]) && sizeof($links[1]))
		{

			$link_model = new Link();
			foreach ($links[1] as $link)
			{
				$link_url = $link_model->getCMSLink($link);
				$cms->content = preg_replace('#{{cms url='.$link.'}}#', $link_url, $cms->content);
			}
		}

		return $cms;
	}

	/**
	 * Récupération de l'arborescence des pages cms
	 *
	 */
	public static function getCmsLinks()
	{
		//Version basique pour l'instant : ne gère qu'un niveau
		$categories = CMSCategory::getRecurseCategory();
		
		$categories_html = '<ul>';
		if ( $categories['children'] ) 
		{
			foreach ($categories['children'] as $child)
			{
				$categories_html .= '<li>'.$child['name'].'
				
				<ul>';
				foreach ($child['cms'] as $child_cms)
					$categories_html .= '<li><a href="#" onclick="addLink(\'{{cms url='.$child_cms['id_cms'].'}}\',\''.$child_cms['meta_title'].'\')">'.$child_cms['meta_title'].'</a></li>';
				
				$categories_html .= '</ul></li>';
			}
		}
		foreach ($categories['cms'] as $cms)
			$categories_html .= '<li><a href="#" onclick="addLink(\'{{cms url='.$cms['id_cms'].'}}\',\''.$cms['meta_title'].'\')">'.$cms['meta_title'].'</a></li>';
		
		$categories_html .= '</ul>';
		
		return $categories_html;
	}
        
        /**
	 * Affichage de la popin TinyMce dans l'admin
	 */
	public function displayTinyMcePopup()
	{
                //Liste des pages cms
		$this->context->smarty->assign('categories_html', $this->getCmsLinks());
                
                //Version de prestashop concernée
                if ( _PS_VERSION_ > '1.6' )
                    $ps_version = '16';
                else
                    $ps_version = '15';
                
                $this->context->smarty->assign('ps_version', $ps_version);
                
		echo $this->display(__FILE__, 'views/tinymce_popup.tpl');
	}

}
