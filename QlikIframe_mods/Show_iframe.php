<?php
//Fix di un bug per cui nel qlick non veniva mostrato il menu di navigazione.
//Sistemato facendo uno smarty display del template del menu,
//inserito anche il nome del record che si sta isualizzando

ob_start();
global $adb,$table_prefix;


$debug = $adb->query("SELECT debug FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1");
$debug = $adb->query_result($debug,0,'debug');

$smarty = new VteSmarty();
$smarty->display("Buttons_List.tpl");

$newurl=$_POST['url_iframe'];
//$newurl=$_REQUEST['url_iframe']; //for params in GET
$newurl=str_replace('|$|','&',$newurl,$count);
$newurl=str_replace("'",'',$newurl,$count);
$newH=$_POST['height'];
//$newH=$_REQUEST['height']; //for params in GET

$sql = "SELECT qlikiframe_name FROM {$table_prefix}_qlikiframe WHERE qlikiframeid = ?";

$res = $adb->pquery($sql, array($_REQUEST['record']));
$nome = $adb->query_result($res, 0, 'qlikiframe_name');

$url_debug = $debug == 1 ? "<a href='{$newurl}' target='_blank'>{$newurl}</a>" : "";


echo "<div><center><h2>$nome</h2></center></div>";

echo $url_debug.'<iframe style="width:100%;height:'.$newH.'px" src='.$newurl.'></iframe>';
?>
