<?php

/**
 * Module Cms Link
 * Mise en place de liens dynamiques vers les pages cms
 * © h-hennes 2013-2015 
 * http://www.h-hennes.fr/blog/
 */

class Tools extends ToolsCore {

    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param string $key Value key
     * @param mixed $default_value (optional)
     * @return mixed Value
     */
    public static function getValue($key, $default_value = false) {

        if (!isset($key) || empty($key) || !is_string($key)) {
            return false;
        }

        $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));


        // array of fields names if you don't want update link ( not need )
        $arrDisableEicLink = ['description_short', 'description'];


        if (is_string($ret)) {
            
            if (!in_array($key, $arrDisableEicLink)) {
                include_once(dirname(__FILE__) . '/../../modules/eicmslinks/eicmslinks.php');
                $ret = EiCmsLinks::updateCmsLinksDisplay($ret);
            }
            return stripslashes(urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret))));
        }

        return $ret;
    }

}
