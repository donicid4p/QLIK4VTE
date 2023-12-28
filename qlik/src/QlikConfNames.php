<?php

global $adb, $qlik_proxy_url, $table_prefix, $current_user;
$frame_perm=false; // per controllo su permesso del singolo record
$result = array();
//control that the module Iframe is active and permitted for the current user
if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){

    $res = $adb->query("SELECT confname FROM ".$table_prefix."_qlikiframeconfs");


    for($i=0;$i<$adb->num_rows($res);$i++){
        $result[$i] = $adb->query_result($res,$i, 'confname');
    }



	echo Zend_Json::encode($result);
}


?>