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

abstract class Widget {
    
    /** @var string $template Nom du fichier de template */
    public $template;
    
    /** @var Context $context Contexte d'éxécution du widget */
    public $context;
    
    /** @var array $_allowed_params Paramètres autorisés ( à surcharger si nécessaires dans les classes filles ) */
    protected static $_allowed_params = array();
    
    /** @var array $datas données du widget */
    protected $_datas = array();
    
    /**
     * Instanciation du widget
     * @param string $params : Paramètres complémentaires du widget
     */
    public function __construct($params) {
        $this->context = Context::getContext();
        $this->parseParams($params);
        $this->initContent();
    }
    
    /**
     * Préparation du contenu
     */
    public function initContent(){
        
    }
    
    /**
     * Traitement des paramètres du widget
     * @param $params
     */
    public function parseParams($params) {
        
        if ( sizeof($params)) {
            
            $params = preg_replace('#\s+#',' ',$params); //Supression des espaces multiples
            $params_array = explode (' ',$params); //Explosion de la chaine sur les espaces
            
            //Traitement des paramètres
            foreach ( $params_array as $param){
                preg_match('#(.*)="(.*)"#',$param,$values); //Récupération de la clé et de la valeur
                
                //Si la clé est autorisée on défini la valeur
                if ( $values[1]) {
                    if ( in_array($values[1],$this->_allowed_params)){
                        $this->_datas[$values[1]] = $values[2];
                    }
                }
            }
            
        }
    }
    
    /**
     * Affichage du contenu
     */
    public function display(){
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'eicmslinks/views/widgets/'.$this->template);
    }
    
    /**
     * Retourne la liste des paramètre autorisé pour le widget
     * @return array Paramètre autorisés
     */
    public static function getAllowedParams(){
        return static::$_allowed_params;
    }
}
