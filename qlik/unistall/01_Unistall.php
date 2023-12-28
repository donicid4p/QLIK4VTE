<?php 

include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;

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

SDK::deleteView('QlikIframe', 'modules/SDK/qlik/src/ViewQlikIframe.php');


$user_block = Vtiger_Block::getInstance('LBL_USER_SENSE_OPTIONS', $moduleInstance=Vtecrm_Module::getInstance('Users'));

if($user_block) {
    $user_block->delete();
}

$moduleInstance=Vtecrm_Module::getInstance('QlikIframe');
if($moduleInstance) {
    Vtecrm_Field::deleteForModule($moduleInstance);
    $moduleInstance->delete();
}

$settingField = Vtiger_SettingsField::getInstance('Qlik for VTE', Vtiger_SettingsBlock::getInstance(3));
$settingField->delete();

$adb->query("DROP TABLE IF EXISTS qlikiframe_analysis_tab");
$adb->query("DROP TABLE IF EXISTS qlikiframe_analysis_tab_seq");

$adb->query("DROP TABLE IF EXISTS qlikiframe_homeqlikanalysis");
$adb->query("DROP TABLE IF EXISTS qlikiframe_homeqlikanalysis_seq");

//{$table_prefix}_qlikiframeconfs
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframeconfs");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframeconfs_seq");

//qlikiframe 
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_seq");

//qlikiframe_active
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_active");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_active_seq");

//qlikiframe_module_related
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_module_related");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_module_related_seq");

//qlikiframe_page_type
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_page_type");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_page_type_seq");

//qlikiframe_position
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_position");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_position_seq");

//qlikiframe_type
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_type");
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframe_type_seq");

//qlikiframecf
$adb->query("DROP TABLE IF EXISTS {$table_prefix}_qlikiframecf");



SDK::deleteLanguageEntry('Users', 'it_it', 'LBL_USER_SENSE_OPTIONS' );
SDK::deleteLanguageEntry('Users', 'en_us', 'LBL_USER_SENSE_OPTIONS' );

SDK::deleteLanguageEntry('Users', 'it_it', 'Login Qlik' );
SDK::deleteLanguageEntry('Users', 'en_us', 'Login Qlik' );

SDK::deleteLanguageEntry('Users', 'it_it', 'Pwd Qlik' );
SDK::deleteLanguageEntry('Users', 'en_us', 'Pwd Qlik' );

SDK::unsetPreSave('QlikIframe','modules/SDK/qlik/src/PresaveQlikIframe.php');



SDK::deleteLanguageEntry('QlikIframe', 'it_it', 'Show in Tab' );
SDK::deleteLanguageEntry('QlikIframe', 'en_us', 'Show in Tab' );

SDK::deleteLanguageEntry('QlikIframe', 'it_it', 'LBL_QLIKIFRAME_TIP_TAB' );
SDK::deleteLanguageEntry('QlikIframe', 'en_us', 'LBL_QLIKIFRAME_TIP_TAB' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'NO_WIDGET_CREATE' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'NO_WIDGET_CREATE' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'NO_ANALYSIS' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'NO_ANALYSIS' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'SELECT_NEW' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'SELECT_NEW' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'CHOOSE_ANALYSIS' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'CHOOSE_ANALYSIS' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'TITLE_NEW_OBJECT' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'TITLE_NEW_OBJECT' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'CREATE_NEW_TAB' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'CREATE_NEW_TAB' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'TAB_ANALYSIS' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'TAB_ANALYSIS' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'ADD_TAB_ANALYSIS' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'ADD_TAB_ANALYSIS' );

SDK::deleteLanguageEntry('ALERT_ARR', 'it_it', 'NO_MODULE_ANALYSIS' );
SDK::deleteLanguageEntry('ALERT_ARR', 'en_us', 'NO_MODULE_ANALYSIS' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'MORE_CAUSES_NO_ANALYSIS' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'MORE_CAUSES_NO_ANALYSIS' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'QLIKIFRAME_NOACTIVE' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'QLIKIFRAME_NOACTIVE' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'QLIKIFRAME_NOPERMITTED' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'QLIKIFRAME_NOPERMITTED' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'GENERAL_ERROR_QLIK' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'GENERAL_ERROR_QLIK' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'DELETED_ANALYSIS' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'DELETED_ANALYSIS' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'CREDENTIALS_WRONG' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'CREDENTIALS_WRONG' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'NO_ACTIVE_ANALYSIS' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'NO_ACTIVE_ANALYSIS' );

SDK::deleteLanguageEntry('APP_STRINGS', 'it_it', 'IFRAMEID_NOT_PERMITTED' );
SDK::deleteLanguageEntry('APP_STRINGS', 'en_us', 'IFRAMEID_NOT_PERMITTED' );


SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF_NAME' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF_NAME' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF_ENDPOIT' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF_ENDPOIT' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF_QRSCertfile' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF_QRSCertfile' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfile' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfile' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfilePassword' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfilePassword' );

SDK::deleteLanguageEntry('Settings', 'it_it', 'LBL_QLIKIFRAME_DESCRIPTION' );
SDK::deleteLanguageEntry('Settings', 'en_us', 'LBL_QLIKIFRAME_DESCRIPTION' );