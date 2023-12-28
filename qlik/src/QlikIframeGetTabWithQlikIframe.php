<?php
//RECUPERA TUTTI I TAB DEFINITI PER ANALISI QLIK IN BASE AL MODULO E ALLA VISTA PASSATI
//SIA PER DETAILVIEW CHE PER LISTVIEW. IN CASO DI LISTVIEW FILTRA ANCHE SU UTENTE CORRENTE
//LE INFO VENGONO RECUPERATE ANCHE DALLA TABELLA DI SUPPORTO.
//MI SERVE POI PER CONTROLLARE SE IL TAB IN CUI MI TROVO SI TROVA IN QUESTO ELENCO O MENO.
global $adb, $table_prefix, $current_user;
$result = array();
$qlikiframe_type = $_REQUEST['qlikiframe_type']; //se detail/listview
$module = $_REQUEST['qlikiframe_mod'];
$tabid =   getTabid($module); //id del modulo corrente

$table='_modulehome';
$tabindex = 'modhomeid';
$type='modulehome';
$qlikiframe_extra = " AND userid= ".$current_user->id." ";
if($qlikiframe_type!="ListView"){
	$table='_panels';
	$tabindex='panelid';
	$type='panels';
	$qlikiframe_extra='';
}
$fulltabname= $table_prefix.$table;
$q_tab= $adb->pquery("SELECT qlikiframe_id
	FROM qlikiframe_analysis_tab
	LEFT JOIN ".$fulltabname." ON qlikiframe_id= ".$tabindex."
	WHERE ".$tabindex." IS NOT NULL
	AND qlikiframe_type = ? 
	AND tabid = ? ".$qlikiframe_extra,array($type,$tabid));
$n_cont = $adb->num_rows($q_tab);
if(isset($q_tab) && $n_cont>0){
	$result[]='Si';
	$qlikiframe_arr = array();
	for($i=0;$i<$n_cont;$i++){
		$id = $adb->query_result($q_tab,$i,'qlikiframe_id');
		$qlikiframe_arr[]=$id;
	}
	$result[]=$qlikiframe_arr; //ho un array di id
}
else{
	$result[]='No';
}

echo Zend_Json::encode($result);
?>