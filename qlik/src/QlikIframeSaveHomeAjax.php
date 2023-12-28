<?php
//SALVATAGGIO DI UN NUOVO WIDGET CON ANALISI QLIK.
//UTILIZZATA PER LA HOMEPAGE.
//SCRIVE IN TABELLE DI SUPPORTO E RICALCA IL SAVE DI WIDGET STANDARD
global $adb, $qlik_proxy_url, $table_prefix, $current_user;
require_once('include/home.php');
$result = array();

$oHomestuff=new Homestuff();
$oHomestuff->stufftype='Qlik';
$oHomestuff->stufftitle=$_REQUEST['title'];

$stuffid=$adb->getUniqueId($table_prefix.'_homestuff');
$queryseq="SELECT max(stuffsequence)+1 AS seq FROM ".$table_prefix."_homestuff";
$sequence=$adb->query_result($adb->pquery($queryseq, array()),0,'seq');

//crmv@fix sequence
if (!$sequence)
	$sequence = 0;
//crmv@fix sequence end

$columns = array('stuffid','stuffsequence','stufftype','userid','visible','stufftitle','size');
$adb->format_columns($columns);
$query="INSERT INTO {$table_prefix}_homestuff (".implode(",",$columns).") VALUES (".generateQuestionMarks($columns).")";
$params= array($stuffid,$sequence,$oHomestuff->stufftype,$current_user->id,0,$oHomestuff->stufftitle,4);
$adb->pquery("INSERT INTO qlikiframe_homeqlikanalysis VALUES(?,?)", array($stuffid,$_REQUEST['qlikiframeid']));
// crmv@30014e crmv@43676e
$r=$adb->pquery($query, $params);

if(!$r){
	$result_ajax[]='No';
}
else{
	$result_ajax[]=$stuffid;
}
$result_ajax[]=$oHomestuff->stufftitle;

echo Zend_Json::encode($result_ajax);

?>