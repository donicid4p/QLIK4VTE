<?php 
//SCRIPT 3 di 3 Aggiunge tutti i nuovi meccanismi disponibili dalla 16.09
include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;

session_start();

global $log, $table_prefix, $adb;
//die('Togli il die!!!');
/* Retrieve instance of SDK module */
$SDKdir = 'modules/SDK/';
$moduleInstance = Vtiger_Module::getInstance('SDK');
if (empty($moduleInstance)) {
	die('Modulo SDK non inizializzato');
}
SDK::clearSessionValues();

//registro un presave sul modulo Iframe
SDK::setPreSave('QlikIframe','modules/SDK/qlik/src/PresaveQlikIframe.php');

//trasformo i campi tipo pagina e posizionamento iframe in uitype 300 Il campo tipo pagina guida i valori del campo posizionamento iframe.
//Aggiungo un nuovo valore --- per il caso che tipo pagina = Modulo
$iframe = Vtiger_Module::getInstance('QlikIframe');
$fieldInstance = Vtiger_Field::getInstance('qlikiframe_position',$iframe);
$fieldInstance->setPicklistValues(array('---'));

$adb->pquery("UPDATE ".$table_prefix."_field SET uitype = 300 WHERE fieldname IN (?,?)", array('qlikiframe_position','qlikiframe_page_type'));

//ora creo i collegamenti:
include('modules/SDK/examples/uitypePicklist/300Utils.php');
linkedListDeleteLink('qlikiframe_position','QlikIframe','qlikiframe_page_type');

linkedListAddLink('qlikiframe_page_type', 'qlikiframe_position', 'QlikIframe','DetailView', array('Top','Bottom'));
linkedListAddLink('qlikiframe_page_type', 'qlikiframe_position', 'QlikIframe','ListView',array('Top','Bottom'));
linkedListAddLink('qlikiframe_page_type', 'qlikiframe_position', 'QlikIframe','Modulo',array('---'));

//creo la tabella di supporto per definire gli iframe in tab, il panel id e la tipologia
$name = "qlikiframe_analysis_tab";
$table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="qlikiframe_id" type="I" size="19"></field>
    <field name="qlikiframeid" type="I" size="19"/>
	<field name="qlikiframe_type" type="C" size="20"/>
  </table>
</schema>';
Vtiger_Utils::ExecuteSchema($table);
$adb->database->GenID($name.'_seq',1);

$adb->pquery("ALTER TABLE qlikiframe_analysis_tab
		CHANGE COLUMN qlikiframe_id qlikiframe_id INT(19) NOT NULL ,
CHANGE COLUMN qlikiframe_type qlikiframe_type VARCHAR(20) NOT NULL ,
ADD PRIMARY KEY (qlikiframe_id, qlikiframe_type)",array());

//tabella di supporto per iframe in home
$name = "qlikiframe_homeqlikanalysis";
$table = '<?xml version="1.0"?>
<schema version="0.3">
  <table name="'.$name.'">
  <opt platform="mysql">ENGINE=InnoDB</opt>
    <field name="homeqlikid" type="I" size="19">
      <KEY/>
    </field>
    <field name="qlikiframeid" type="I" size="19"/>

  </table>
</schema>';
Vtiger_Utils::ExecuteSchema($table);
$adb->database->GenID($name.'_seq',1);

//creo il nuovo campo per definire se l'iframe è da impostare nella tab o meno.
$fields = array();
$fields[] = array('module'=>'QlikIframe',
		'block'=>'LBL_QLIKIFRAME_INFORMATION',
		'name'=>'qlikiframe_show_in_tab',
		'label'=>'Show in Tab',
		'uitype'=>'56',
		'columntype'=>'I(1)',
		'typeofdata'=>'C~O',
		'helpinfo' =>'LBL_QLIKIFRAME_TIP_TAB',
		'quickcreate'=>3);

include('modules/SDK/examples/fieldCreate.php');

//label varie
SDK::setLanguageEntries('QlikIframe', 'Show in Tab', array('it_it'=>'Mostra Analisi in Tab','en_us'=>'Show Analysis in Tab'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_TIP_TAB', array('it_it'=>'Se check, per visualizzare l\'analisi, occorre creare una nuova tab','en_us'=>'If checked, to see the analysis must create a new tab'));

SDK::setLanguageEntries('ALERT_ARR','NO_WIDGET_CREATE', array('it_it'=>'Qualcosa non va, impossibile creare il widget','en_us'=>'Something was wrong: impossible create widget'));
SDK::setLanguageEntries('ALERT_ARR','NO_ANALYSIS', array('it_it'=>'Non ci sono analisi disponibili','en_us'=>'No Analisys available'));
SDK::setLanguageEntries('ALERT_ARR','SELECT_NEW', array('it_it'=>'Seleziona Analisi Qlik','en_us'=>'Select Qlik Analysis'));

SDK::setLanguageEntries('ALERT_ARR','CHOOSE_ANALYSIS', array('it_it'=>'Analisi Qlik','en_us'=>'Qlik Analysis'));
SDK::setLanguageEntries('ALERT_ARR','TITLE_NEW_OBJECT', array('it_it'=>'Nome','en_us'=>'Name'));
SDK::setLanguageEntries('ALERT_ARR','CREATE_NEW_TAB', array('it_it'=>'Crea','en_us'=>'Create'));
SDK::setLanguageEntries('ALERT_ARR','TAB_ANALYSIS', array('it_it'=>'Nuovo tab con Analisi Qlik','en_us'=>'New tab with Qlik Analysis'));

SDK::setLanguageEntries('ALERT_ARR','ADD_TAB_ANALYSIS', array('it_it'=>'Aggiungi tab con analisi qlik','en_us'=>'Add qlik analysis tab'));
SDK::setLanguageEntries('ALERT_ARR','NO_MODULE_ANALYSIS', array('it_it'=>'Modulo Analisi Qlik non attivo o accesso al modulo non permesso','en_us'=>'Qlik Analysis module inactive or permission denied to module'));
SDK::setLanguageEntries('APP_STRINGS','MORE_CAUSES_NO_ANALYSIS', array('it_it'=>"Analisi non disponibile: risulta essere cancellata oppure non disponibile per la visualizzazione in tab o per il modulo corrente.",'en_us'=>"Analysis not available: Analysis selected deleted or not available to show in a tab or for current module"));
SDK::setLanguageEntries('APP_STRINGS','QLIKIFRAME_NOACTIVE', array('it_it'=>"Modulo Analisi Qlik non attivo",'en_us'=>"Qlik Analysis module inactive"));
SDK::setLanguageEntries('APP_STRINGS','QLIKIFRAME_NOPERMITTED', array('it_it'=>"Accesso al modulo Analisi Qlik non permesso",'en_us'=>"Permission denied to Qlik Analysis module"));

SDK::setLanguageEntries('APP_STRINGS','GENERAL_ERROR_QLIK', array('it_it'=>'Qualcosa non va, riprovare','en_us'=>'Something was wrong, retry'));
SDK::setLanguageEntries('APP_STRINGS','DELETED_ANALYSIS', array('it_it'=>'L\'analisi selezionata risulta cancellata','en_us'=>'Analysis selected deleted'));
SDK::setLanguageEntries('APP_STRINGS','CREDENTIALS_WRONG', array('it_it'=>'Credenziali non valide per l\'analisi selezionata. Controllare le impostazioni utente','en_us'=>'Qlik credentials wrong. See user\'s settings'));
SDK::setLanguageEntries('APP_STRINGS','NO_ACTIVE_ANALYSIS', array('it_it'=>'L\'analisi selezionata risulta non attiva','en_us'=>'Analysis selected inactive'));
SDK::setLanguageEntries('APP_STRINGS','IFRAMEID_NOT_PERMITTED', array('it_it'=>'Iframe non accessibile','en_us'=>'Iframe not available'));

//ordino la visualizzazione dei campi per il modulo iframe
$iframe = Vtiger_Module::getInstance('QlikIframe');
$iframeid= $iframe->id;
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 3 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_txt_mod_name'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 4 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_type'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 5 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_active'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 6 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_show_in_tab'));
$adb->pquery("UPDATE ".$table_prefix."_field SET masseditable = 0, sequence = 7 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_page_type'));
$adb->pquery("UPDATE ".$table_prefix."_field SET masseditable = 0, sequence = 8 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_position'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 9 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_height'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 10 WHERE tabid = ? and fieldname = ? ", array($iframeid,'createdtime'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 11 WHERE tabid = ? and fieldname = ? ", array($iframeid,'modifiedtime'));
$adb->pquery("UPDATE ".$table_prefix."_field SET sequence = 12 WHERE tabid = ? and fieldname = ? ", array($iframeid,'assigned_user_id'));
$adb->pquery("UPDATE ".$table_prefix."_field SET masseditable = 0 WHERE tabid = ? and fieldname = ? ", array($iframeid,'qlikiframe_module_related'));

?>