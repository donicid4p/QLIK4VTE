<?php 
include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
		require_once('vtlib/Vtiger/SettingsBlock.php');
		require_once('vtlib/Vtiger/SettingsField.php');
$Vtiger_Utils_Log = true;

session_start();

global $log, $table_prefix, $adb;


$block = Vtiger_SettingsBlock::getInstance(3);

$field = new Vtiger_SettingsField();
$field->name = 'Qlik for VTE';
$field->description = 'Configurazione Qlik for VTE';
$field->linkto = 'index.php?module=Settings&action=QlikIframe';
$field->save($block);



$name = "{$table_prefix}_qlikiframeconfs";

$table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="conf_id" type="I" size="19"></field>
    <field name="confname" type="C" size="255"/>
<field name="QRSurl" type="C" size="255"/>
<field name="active" type="I" size="1"/>
	<field name="endpoint" type="C" size="255"/>
<field name="QRSCertfile" type="C" size="255"/>
<field name="QRSCertkeyfile" type="C" size="255"/>
<field name="QRSCertkeyfilePassword" type="C" size="255"/>
<field name="debug" type="I" size="1"/>
  </table>
</schema>';
Vtiger_Utils::ExecuteSchema($table);
$adb->database->GenID($name.'_seq',1);



SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF', array('it_it'=>'Qlik','en_us'=>'Qlik'));

SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF_NAME', array('it_it'=>'Nome configurazione Qlik','en_us'=>'Qlik configuration name'));
SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF_ENDPOIT', array('it_it'=>'Qlik endpoint','en_us'=>'Qlik endpoint'));
SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF_QRSCertfile', array('it_it'=>'Qlik QRSCertfile','en_us'=>'Qlik QRSCertfile'));
SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfile', array('it_it'=>'Qlik QRSCertkeyfile','en_us'=>'Qlik QRSCertkeyfile'));
SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_CONF_QRSCertkeyfilePassword', array('it_it'=>'Qlik QRSCertkeyfilePassword','en_us'=>'Qlik QRSCertkeyfilePassword'));


SDK::setLanguageEntries('Settings', 'LBL_QLIKIFRAME_DESCRIPTION', array('it_it'=>'Configura Qlik','en_us'=>'Configure Qlik'));


