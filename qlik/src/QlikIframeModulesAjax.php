<?php
// retrieve module active
global $adb, $qlik_proxy_url, $table_prefix, $current_user;
$result=array();
// modificata clausola su nome Home e settings: post aggiunta wizard.
$query = 'SELECT distinct '.$table_prefix.'_tab.tablabel, '.$table_prefix.'_tab.name AS tabname 
		FROM '.$table_prefix.'_tab 
		WHERE '.$table_prefix.'_tab.presence <> 1 
		AND '.$table_prefix.'_tab.name NOT IN ("QlikIframe","Home","Settings") 
		AND '.$table_prefix.'_tab.isentitytype <> 0';
$mod_selected='nothing';
$q_mod_sel= "SELECT qlikiframe_module_related FROM ".$table_prefix."_qlikiframe WHERE qlikiframeid = ".$_REQUEST['rid'];
if(isset($_REQUEST['rid']) && $_REQUEST['rid'] != ''){
	$res_module = $adb->query($q_mod_sel);

	if(isset($res_module)&& $adb->num_rows($res_module)>0){
		$mod_selected =$adb->query_result($res_module,0,'qlikiframe_module_related');
	}
}
$result[]=array('mod_selected',$mod_selected);
//$result[]=$query;
$resultquery = $adb->query($query);

while($row = $adb->fetch_array($resultquery)){
	if(isPermitted($row['tabname'],'')!= "no") // aggiungo solo i moduli permessi a quell'utente che sta creando il record
		$result[]= array($row['tabname'],getTranslatedString($row['tablabel'],$row['name'])); // sostituito tabname con name
}

echo Zend_Json::encode($result);
?>