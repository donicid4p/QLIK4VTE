<?php 
//SCRIPT 2 DI 3 HA IL COMPITO di INSTALLARE IL CAMPO NASCOSTO CHE TIENE IL NOME DEL MODULO E TUTTI I MECCANISMI PER MANTENERLO AGGIORNATO
// SEGUE:
// 03_Improvement_QlikIframe_1432.php
/* Let's include some handy stuff */
include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;

/* Start the session, in order to allow SDK to update values stored in 
 * $_SESSION array. SDK uses the session to store values when updating 
 * to speed up queries.
 * If session has not started, you need to log out and login every time a 
 * SDK method is called. */
session_start();

global $log, $table_prefix, $adb;

/* Retrieve instance of SDK module */
$SDKdir = 'modules/SDK/';
$moduleInstance = Vtiger_Module::getInstance('SDK');
if (empty($moduleInstance)) {
	die('Modulo SDK non inizializzato');
}

// Clears previous SDK values in the session array
SDK::clearSessionValues();

$fields = array();

$fields[] = array('module'=>'QlikIframe',
		'block'=>'LBL_QLIKIFRAME_INFORMATION',
		'name'=>'qlikiframe_txt_mod_name',
		'label'=>'Txt Mod Name',
		'uitype'=>'1',
		'columntype'=>'C(255)',
		'typeofdata'=>'V~O'
);

include('modules/SDK/examples/fieldCreate.php');

SDK::setLanguageEntries('QlikIframe', 'Txt Mod Name', array('it_it'=>'Modulo','en_us'=>'Module'));

$adb->query("UPDATE ".$table_prefix. "_qlikiframe SET qlikiframe_txt_mod_name = qlikiframe_module_related");

// aggiungo evento after save che va ad aggiornare il campo qlikiframe_txt_mod_name con il valore presente nel campo qlikiframe_module_related
require 'include/events/include.inc';
$em = new VTEventsManager($adb);
$em->registerHandler('vtiger.entity.aftersave', "modules/SDK/qlik/src/QlikHandler.php", 'QlikHandler');

?>
