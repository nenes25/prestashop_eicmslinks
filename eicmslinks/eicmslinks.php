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
        $this->version = '0.9.0';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Ei Cms Links');
        $this->description = $this->l('Add Cms Links tools in tinyMce Editor');
    }

    public function install() {
        if (!parent::install() || !Configuration::updateValue('eicmslinks_admin_path', 0))
            return false;

        //Copie des dossier de l'editeur tinyMce
        $this->copyDir(dirname(__FILE__) . '/tiny_mce/', dirname(__FILE__) . '/../../js/tiny_mce/plugins/');

        //Copie de l'override du formulaire cms de l'admin (Normalement devrait fonctionner via prestashop)
        $this->copyDir(dirname(__FILE__) . '/override/controllers/admin/templates/', dirname(__FILE__) . '/../../override/controllers/admin/templates/');

        //Création d'une tab prestashop ( nécessaire pour le controller back office )
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

        //Spécifique 1.5 ( on renomme le fichier de surcharge avec le bon nom car ils ne sont pas compatibles entre les versions )
        if (_PS_VERSION_ < '1.6') {
            rename(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form.tpl', dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form16.tpl');
            rename(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form15.tpl', dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form.tpl');
        }
        return true;
    }

    public function uninstall() {

        if (!parent::uninstall())
            return false;

        //Suppression des fichiers lors de la désinstallation
        if (!$this->deleteDir(dirname(__FILE__) . '/../../js/tiny_mce/plugins/eicmslinks/'))
            return false;

        //Suppression des overrides admin (non bloquant)
        $this->_unInstallFiles();

        //Suppression de la tab admin
        $id_tab = Tab::getIdFromClassName('wysiwyg');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }

        return true;
    }

    /**
     * Soumission de la configuration dans l'admin
     */
    public function postProcess() {
        if (Tools::isSubmit('SubmitConfiguration')) {
            Configuration::updateValue('eicmslinks_admin_path', Tools::getValue('eicmslinks_admin_path'));
            $this->html .= $this->displayConfirmation($this->l('Settings updated'));
        }
    }

    /**
     * Configuration du module
     */
    public function getContent() {
        $this->html .= $this->postProcess();
        $this->html .= $this->renderForm();

        return $this->html;
    }

    /**
     * Formulaire de configuration du module
     */
    public function renderForm() {

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Ei cms Links Configuration'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('In order to works properly the module needs to know your adminPath (without slash) ie : admin-dev'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Admin Path'),
                        'name' => 'eicmslinks_admin_path',
                        'required' => true,
                        'empty_message' => $this->l('Please fill the captcha private key'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'button btn btn-default pull-right',
                ),
        ));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->id = 'eicmslinks';
        $helper->submit_action = 'SubmitConfiguration';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues() {
        if (Configuration::get('eicmslinks_admin_path') == '0') {
            $currentPath = getcwd();
            $paths = explode('/', $currentPath);
            $adminDir = $paths[sizeof($paths) - 1];
        } else {
            $adminDir = Configuration::get('eicmslinks_admin_path');
        }

        return array('eicmslinks_admin_path' => Tools::getValue('eicmslinks_admin_path', $adminDir));
    }

    /**
     * Copie du contenu d'un dossier vers un autre emplacement
     * @param string $dir2copy : Chemin du dossier à copier
     * @param string $dir_paste : Chemin vers lequel copier le dossier
     * @return void
     */
    public function copyDir($dir2copy, $dir_paste) {
        if (is_dir($dir2copy)) {
            if ($dhd = opendir($dir2copy)) {
                while (($file = readdir($dhd)) !== false) {
                    if (!is_dir($dir_paste))
                        $create_dir = mkdir($dir_paste, 0777);

                    if (is_dir($dir2copy . $file) && $file != '..' && $file != '.')
                        $this->copyDir($dir2copy . $file . '/', $dir_paste . $file . '/');
                    elseif ($file != '..' && $file != '.')
                        $copy_file = copy($dir2copy . $file, $dir_paste . $file);
                }
                closedir($dhd);
            }
        }
    }

    /**
     * Supression récursive d'un dossier
     * @param type $dir
     * @return boolean
     */
    public function deleteDir($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * Mise à jour de l'objet cms pour remplacer les variables d'url des lien
     * @param string : contenu ou il faut remplacer les liens
     * @return string : contenu avec les liens remplacés
     */
    public static function updateCmsLinksDisplay($content = null) {
        //Inclusion de la classe des widgets
        include_once dirname(__FILE__) . '/classes/Widget.php';

        if ($content === null)
            return;

        //Dans prestashop 1.6 les caractères { et } sont encodés
        if (_PS_VERSION_ > '1.6')
            $content = urldecode($content);

        $link_model = new Link();

        //Mise à jour des liens vers les pages cms
        preg_match_all('#{{cms url=([0-9])}}#', $content, $cms_links);

        if (isset($cms_links[1]) && sizeof($cms_links[1])) {
            foreach ($cms_links[1] as $link) {
                $link_url = $link_model->getCMSLink($link);
                $content = preg_replace('#{{cms url=' . $link . '}}#', $link_url, $content);
            }
        }

        //Mise à jour des liens vers les pages categories
        preg_match_all('#{{category url=([0-9])}}#', $content, $category_links);

        if (isset($category_links[1]) && sizeof($category_links[1])) {
            foreach ($category_links[1] as $category_link) {
                $category_link_url = $link_model->getCategoryLink($category_link);
                $content = preg_replace('#{{category url=' . $category_link . '}}#', $category_link_url, $content);
            }
        }

        //Mise à jour des liens vers les pages produits
        preg_match_all('#{{product url=([0-9])}}#', $content, $product_links);

        if (isset($product_links[1]) && sizeof($product_links[1])) {
            foreach ($product_links[1] as $product_link) {
                $product_link_url = $link_model->getProductLink($product_link);
                $content = preg_replace('#{{product url=' . $product_link . '}}#', $product_link_url, $content);
            }
        }

        //Mise à jour des liens d'ajout au panier
        preg_match_all('#{{cart url=([0-9])}}#', $content, $product_links);

        if (isset($product_links[1]) && sizeof($product_links[1])) {
            foreach ($product_links[1] as $product_link) {
                $product_cart_url = sprintf('index.php?controller=cart&add=1&qty=1&id_product=%s&token=%s', $product_link, Tools::getToken());
                $content = preg_replace('#{{cart url=' . $product_link . '}}#', $product_cart_url, $content);
            }
        }

        //Gestion des widgets
        preg_match_all('#{{widget name="(.*)"(.*)}}#U', $content, $widgets);

        if (isset($widgets[1]) && sizeof($widgets[1])) {
            $i = 0;
            foreach ($widgets[1] as $widget) {
                $widget = trim($widget);
                if (is_file(dirname(__FILE__) . '/classes/' . $widget . '.php')) {
                    include_once dirname(__FILE__) . '/classes/' . $widget . '.php';
                    $widgetParams = $widgets[2][$i];
                    try {
                        $widgetObject = new $widget($widgetParams);
                        $widgetContent = $widgetObject->display();
                        $content = str_replace('{{widget name="' . $widget . '"' . $widgetParams . '}}', $widgetContent, $content);
                    } catch (PrestaShopExceptionCore $e) {
                        echo $e->getMessage();
                    }
                }
                $i++;
            }
        }

        return $content;
    }

    /**
     * Récupération de l'arborescence des pages cms
     *
     */
    public static function getCmsLinks() {
        //Version basique pour l'instant : ne gère qu'un niveau
        $categories = CMSCategory::getRecurseCategory();

        $categories_html = '<ul>';
        if ($categories['children']) {
            foreach ($categories['children'] as $child) {
                $categories_html .= '<li>' . $child['name'] . '
				
				<ul>';
                foreach ($child['cms'] as $child_cms)
                    $categories_html .= '<li><a href="#" onclick="addLink(\'{{cms url=' . $child_cms['id_cms'] . '}}\')">' . $child_cms['meta_title'] . '</a></li>';

                $categories_html .= '</ul></li>';
            }
        }
        foreach ($categories['cms'] as $cms)
            $categories_html .= '<li><a href="#" onclick="addLink(\'{{cms url=' . $cms['id_cms'] . '}}\')">' . $cms['meta_title'] . '</a></li>';

        $categories_html .= '</ul>';

        return $categories_html;
    }

    /**
     * Récupération de la liste des widgets
     * @return array $widgets
     */
    public function getWidgetsList() {

        $class_dir = dirname(__FILE__) . '/classes/';

        $widgets = array();

        include_once $class_dir . 'Widget.php';

        $op = opendir($class_dir);
        while ($file = readdir($op)) {
            if (preg_match('#^Widget(.*)\.php#', $file, $widget_name) && $file != 'Widget.php') {
                include_once $class_dir . $file;
                $className = 'Widget' . $widget_name[1];
                $widgets[] = array(
                    'name' => $widget_name[1],
                    'params' => $className::getAllowedParams()
                );
            }
        }

        return $widgets;
    }

    /**
     * Affichage de la popin TinyMce dans l'admin
     */
    public function displayTinyMcePopup() {

        //Liste des pages cms
        $this->context->smarty->assign('cms_categories_html', $this->getCmsLinks());
        //Liste des widgets du module
        $this->context->smarty->assign('widgets_list', $this->getWidgetsList());

        //Token Admin ( celui récupéré automatiquement ne fonctionne pas )
        $cookie = new Cookie('psAdmin');
        $token = Tools::getAdminToken('Wysiwyg' . (int) Tab::getIdFromClassName('Wysiwyg') . (int) $cookie->id_employee);
        $ajax_page = $this->context->link->getAdminLink('module=eicmslinks&controller=Wysiwyg&ajax=1', false);

        $this->context->smarty->assign('js_token', $token);
        $this->context->smarty->assign('ajax_page', $ajax_page);
        $this->context->smarty->assign('admin_dir', Configuration::get('eicmslinks_admin_path'));

        //Js nécessaires au fonctionnement de la popin
        $jquery_files = Media::getJqueryPath();
        $this->context->smarty->assign('jquery_file', $jquery_files[0]);
        $this->context->smarty->assign('js_file', __PS_BASE_URI__ . 'modules/' . $this->name . '/js/tinymce_popup.js');
        $this->context->smarty->assign('css_file', __PS_BASE_URI__ . 'modules/' . $this->name . '/css/tinymce_popup.css');

        //Version de prestashop concernée
        if (_PS_VERSION_ > '1.6')
            $ps_version = '16';
        else
            $ps_version = '15';

        $this->context->smarty->assign('ps_version', $ps_version);

        echo $this->display(__FILE__, 'views/tinymce_popup.tpl');
    }

    /**
     * Suppression des fichiers de surcharge admin lors de la desinstallation du module
     * On supprime uniquement les fichiers, car il est possible que d'autres modules surchargent un autre template
     */
    protected function _unInstallFiles() {

        $deleted = true;

        //Si la suppression du template admin si la desinstall prestashop n'a pas fonctionné on le supprime 
        if (is_file(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form.tpl'))
            if (!unlink(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form.tpl'))
                $deleted = false;

        if (_PS_VERSION_ < '1.6') {
            if (is_file(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form16.tpl'))
                ;
            if (!unlink(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form16.tpl'))
                $deleted = false;
        }
        else {
            if (is_file(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form15.tpl'))
                ;
            if (!unlink(dirname(__FILE__) . '/../../override/controllers/admin/templates/cms/helpers/form/form15.tpl'))
                $deleted = false;
        }

        if (is_file(dirname(__FILE__) . '/../../override/controllers/admin/templates/categories/helpers/form/form.tpl'))
            if (!unlink(dirname(__FILE__) . '/../../override/controllers/admin/templates/categories/helpers/form/form.tpl'))
                $deleted = false;

        if (is_file(dirname(__FILE__) . '/../../override/controllers/admin/templates/products/helpers/form/form.tpl'))
            if (!unlink(dirname(__FILE__) . '/../../override/controllers/admin/templates/products/helpers/form/form.tpl'))
                $deleted = false;

        return $deleted;
    }

}
