<?php
require_once(dirname(__FILE__).'/../../../../config/config.inc.php');

$adminDir = Configuration::get('eicmslinks_admin_path');
$redirectTo = Tools::getHttpHost(true).__PS_BASE_URI__.$adminDir."/ajax-tab.php?".$_SERVER['QUERY_STRING'];
header("location: $redirectTo");