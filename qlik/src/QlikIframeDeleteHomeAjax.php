<?php 
//SI OCCUPA DI RIMUOVERE IL RECORD NELLA TABELLA DI SUPPORTO PER LE WIDGET IN HOMEPAGE
global $adb, $table_prefix, $current_user;

$stuffid = $_REQUEST['qlikiframe_stuffid'];

$adb->pquery("DELETE FROM qlikiframe_homeqlikanalysis WHERE homeqlikid=?",array($stuffid));

?>