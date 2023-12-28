<?php 
global $adb, $table_prefix, $current_user;
//SI OCCUPA DI RIMUOVERE IL RECORD NELLA TABELLA DI SUPPORTO PER LE TAB DEDICATE.
$type = $_REQUEST['qlikiframe_type'];
$id = $_REQUEST['qlikiframe_id'];

$adb->pquery("DELETE FROM qlikiframe_analysis_tab WHERE qlikiframe_id=? AND qlikiframe_type=?",array($id,$type));

?>