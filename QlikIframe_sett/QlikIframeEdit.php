<?php


require_once('modules/Settings/QlikIframe/core/QlikIframeInfo.php');

global $app_strings, $mod_strings, $currentModule, $theme, $current_language;

$smarty = new VteSmarty();
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH","themes/$theme/images/");

$qlikconf = $_REQUEST['confname'];

//crmv@56233
(empty($qlikconf)) ? $mode = '' : $mode = 'edit';
$smarty->assign("SAVEMODE", $mode);
//crmv@56233e

$confinfo = new QlikIframeInfo($qlikconf);



$smarty->assign("CONFINFO", $confinfo->getAsMap());


$smarty->display('QlikIframe/QlikIframeEdit.tpl');

?>
