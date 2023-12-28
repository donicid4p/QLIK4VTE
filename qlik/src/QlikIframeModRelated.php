<?php
/* -- COMMENTATO IN QUANTO NON IN USO
global $adb, $qlik_proxy_url, $table_prefix, $current_user;

$res = $adb->pquery("select qlikiframe_module_related 
		from ".$table_prefix."_qlikiframe
		inner join ".$table_prefix."_qlikiframecf on ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
		inner join ".$table_prefix."_crmentity on ".$table_prefix."_qlikiframe.qlikiframeid = crmid and deleted = 0 
		where ".$table_prefix."_qlikiframe.qlikiframeid = ?",array($_REQUEST['record']));
$result = array();
if($res && $adb->num_rows($res)>0){

	$result[]=$adb->query_result($res,0,'qlikiframe_module_related'); // modifica di $i con 0 e modifica assegnazione
}
	

echo Zend_Json::encode($result);

*/
?>
