<?php 
include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;

session_start();

global $log, $table_prefix, $adb;



if (SDK::isUitype(300)) {
 echo "Uitype 300 already exists";
} else {
   echo "Creating Uitype 300";
SDK::setUitype(300, 'modules/SDK/examples/uitypePicklist/300.php', 'modules/SDK/examples/uitypePicklist/300.tpl', 'modules/SDK/examples/uitypePicklist/300.js', $type='', $params='');
}






//SDK::unsetUitype(300);