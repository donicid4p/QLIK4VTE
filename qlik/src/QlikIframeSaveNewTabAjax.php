<?php
//SALVATAGGIO DI UNA NUOVA TAB CON ANALISI QLIK.
//UTILIZZATA SIA PER LA LISTVIEW CHE PER LA DETAIL.
//SCRIVE IN TABELLE DI SUPPORTO
global $adb, $table_prefix, $current_user;

$result_ajax = array();
$qlikiframe_type = $_REQUEST['qlikiframe_type'];
$tabid =   getTabid($_REQUEST['qlikiframe_mod']);
$name = $_REQUEST['title'];
$iframeid= $_REQUEST['qlikiframeid'];
$columns = array('modhomeid','userid','tabid','name');
$table='_modulehome';
$sequence = 2;
$type='modulehome';
$params = array('xx',$current_user->id,$tabid,$name);
if($qlikiframe_type!= "ListView"){
	$table='_panels';
	$type='panels';
	$columns = array('panelid','tabid','panellabel','sequence');
	$q_seq = $adb->pquery ("SELECT MAX(sequence)+1  AS seq FROM ".$table_prefix."_panels WHERE tabid=?",array($tabid));
	if(isset($q_seq)&& $adb->num_rows($q_seq)>0){
		$sequence = $adb->query_result($q_seq,0,'seq');
	}
	$params = array('xx',$tabid,$name,$sequence);
}

$fulltabname = $table_prefix.$table;


$genQueryId = $adb->getUniqueID($fulltabname);
$params[0]=$genQueryId; //sostituisco xx con quello corretto

$adb->format_columns($columns);
$query="INSERT INTO {$fulltabname} (".implode(",",$columns).") VALUES (".generateQuestionMarks($columns).")";

$r=$adb->pquery($query, $params);

$adb->pquery("INSERT INTO qlikiframe_analysis_tab VALUES(?,?,?)", array($genQueryId,$iframeid,$type));


if(!$r){
	$result_ajax[]='No';
}
else{
	$result_ajax[]=$genQueryId;
}
$result_ajax[]=$name;

echo Zend_Json::encode($result_ajax);

?>