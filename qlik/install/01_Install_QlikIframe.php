<?php 
//SCRIPT 1 DI 3 HA IL COMPITO DI INSTALLARE IL MODULO
// SEGUONO:
// 02_Upgrade_QlikIframe.php
// 03_Improvement_QlikIframe_1432.php
// start Creazione modulo QlikIframe
/* Let's include some handy stuff */
include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;
//die('RICORDA: inserisci nel file config.php la variabile $qlik_proxy_url e inizializzala con l\'url del proxy sense');
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

// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'QlikIframe';
$module->save();

// Initialize all the tables required
$module->initTables();

// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Tools');
$menu->addModule($module);

// Add the basic module block
$block1 = new Vtiger_Block();
$block1->label = 'LBL_QLIKIFRAME_INFORMATION';
$module->addBlock($block1);

//add filter block
$block2 = new Vtiger_Block();
$block2->label = 'LBL_QLIKIFRAME_FILTER1';
$module->addBlock($block2);

$block3 = new Vtiger_Block();
$block3->label = 'LBL_QLIKIFRAME_FILTER2';
$module->addBlock($block3);

$block4 = new Vtiger_Block();
$block4->label = 'LBL_QLIKIFRAME_FILTER3';
$module->addBlock($block4);

$block5 = new Vtiger_Block();
$block5->label = 'LBL_QLIKIFRAME_FILTER4';
$module->addBlock($block5);

$block6 = new Vtiger_Block();
$block6->label = 'LBL_QLIKIFRAME_FILTER5';
$module->addBlock($block6);

$block7 = new Vtiger_Block();
$block7->label = 'LBL_QLIKIFRAME_FILTER6';
$module->addBlock($block7);

$block8 = new Vtiger_Block();
$block8->label = 'LBL_QLIKIFRAME_FILTER7';
$module->addBlock($block8);

/** Create required fields and add to the block */
//FRAME NAME
$field0 = new Vtiger_Field();
$field0->name = 'qlikiframe_name';
$field0->table = $module->basetable;
$field0->label= 'IFrame Name'; 
$field0->columntype = 'C(255)';
$field0->uitype = 1;
$field0->typeofdata = 'V~M';
$field0->quickcreate = 3;
$block1->addField($field0);

// Set at-least one field to identifier of module record
$module->setEntityIdentifier($field0);

//LINK (URI)
$field1 = new Vtiger_Field();
$field1->name = 'qlikiframe_link'; 
$field1->table = $module->basetable;
$field1->label= 'Link';
$field1->columntype = 'C(255)';
$field1->uitype = 1;
$field1->typeofdata = 'V~O';
$field1->helpinfo = 'LBL_TIP_URI';
$field1->quickcreate = 2;
$block1->addField($field1);


//RELATED MODULE
$field2 = new Vtiger_Field();
$field2->name = 'qlikiframe_module_related';
$field2->table = $module->basetable;
$field2->label= 'Modulo';
$field2->columntype = 'C(255)';
$field2->uitype = 15;
$field2->typeofdata = 'V~O';
$field2->quickcreate = 2;
$block1->addField($field2);
$field2->setPicklistValues(Array('-- Nessuno --'));

//HEIGHT
$field3 = new Vtiger_Field();
$field3->name = 'qlikiframe_height'; 
$field3->table = $module->basetable;
$field3->label= 'Height';
$field3->columntype = 'N(5.0)';
$field3->uitype = 7;
$field3->typeofdata = 'N~O';
$field3->quickcreate = 2;
$block1->addField($field3);

//TYPE OF ANALYSIS 
$field4 = new Vtiger_Field();
$field4->name = 'qlikiframe_type'; 
$field4->table = $module->basetable;
$field4->label= 'Analysis Type';
$field4->columntype = 'C(255)';
$field4->uitype = 15;
$field4->typeofdata = 'V~O';
$field4->quickcreate = 2;
$block1->addField($field4);
$field4->setPicklistValues(Array('Qlik Sense','Altro'));

//IFRAME ACTIVE
$field5 = new Vtiger_Field();
$field5->name = 'qlikiframe_active';
$field5->table = $module->basetable;
$field5->label= 'Active';
$field5->columntype = 'C(255)';
$field5->uitype = 15;
$field5->typeofdata = 'V~O';
$field5->quickcreate = 2;
$block1->addField($field5);
$field5->setPicklistValues(Array('Si','No'));

//POSITION IN PAGE
$field6 = new Vtiger_Field();
$field6->name = 'qlikiframe_position';
$field6->table = $module->basetable;
$field6->label= 'Position';
$field6->columntype = 'C(255)';
$field6->uitype = 15;
$field6->typeofdata = 'V~O';
$field6->quickcreate = 2;
$block1->addField($field6);
$field6->setPicklistValues(Array('Top','Bottom'));


//TYPE OF PAGE
$field7 = new Vtiger_Field();
$field7->name = 'qlikiframe_page_type';
$field7->table = $module->basetable;
$field7->label= 'Page Type';
$field7->columntype = 'C(255)';
$field7->uitype = 15;
$field7->typeofdata = 'V~O';
$field7->quickcreate = 2;
$block1->addField($field7);
$field7->setPicklistValues(Array('DetailView','ListView','Modulo'));

//*****************************************************
//					FIELDS FOR FILTERS
//*****************************************************

//************ I filtro ******************
 //check 1
$field8 = new Vtiger_Field();
$field8->name = 'qlikiframe_check_1';
$field8->table = $module->basetable;
$field8->label= 'Check 1';
$field8->columntype = 'I(1)';
$field8->uitype = 56;
$field8->typeofdata = 'C~O';
$field8->quickcreate = 3;
$block2->addField($field8);

//sense field 1
$field9 = new Vtiger_Field();
$field9->name = 'qlikiframe_qlik_f1';
$field9->table = $module->basetable;
$field9->label= 'Qlik f1';
$field9->columntype = 'C(255)';
$field9->uitype = 1;
$field9->typeofdata = 'V~O';
$field9->quickcreate = 3;
$block2->addField($field9);

//vte_field 1
$field10 = new Vtiger_Field();
$field10->name = 'qlikiframe_value1';
$field10->table = $module->basetable;
$field10->label= 'Value 1';
$field10->columntype = 'C(255)';
$field10->uitype = 1;
$field10->typeofdata = 'V~O';
$field10->quickcreate = 3;
$field10->helpinfo = 'LBL_VALUE';
$block2->addField($field10);


//************ II filtro ******************
//check 2
$field11 = new Vtiger_Field();
$field11->name = 'qlikiframe_check_2';
$field11->table = $module->basetable;
$field11->label= 'Check 2';
$field11->columntype = 'I(1)';
$field11->uitype = 56;
$field11->typeofdata = 'C~O';
$field11->quickcreate = 3;
$block3->addField($field11);

//sense field 2
$field12 = new Vtiger_Field();
$field12->name = 'qlikiframe_qlik_f2';
$field12->table = $module->basetable;
$field12->label= 'Qlik f2';
$field12->columntype = 'C(255)';
$field12->uitype = 1;
$field12->typeofdata = 'V~O';
$field12->quickcreate = 3;
$block3->addField($field12);

//vte_field 2
$field13 = new Vtiger_Field();
$field13->name = 'qlikiframe_value2';
$field13->table = $module->basetable;
$field13->label= 'Value 2';
$field13->columntype = 'C(255)';
$field13->uitype = 1;
$field13->typeofdata = 'V~O';
$field13->quickcreate = 3;
$field13->helpinfo = 'LBL_VALUE';
$block3->addField($field13);

//************ III filtro ******************
//check 3
$field14 = new Vtiger_Field();
$field14->name = 'qlikiframe_check_3';
$field14->table = $module->basetable;
$field14->label= 'Check 3';
$field14->columntype = 'I(1)';
$field14->uitype = 56;
$field14->typeofdata = 'C~O';
$field14->quickcreate = 3;
$block4->addField($field14);

//sense field 3
$field15 = new Vtiger_Field();
$field15->name = 'qlikiframe_qlik_f3';
$field15->table = $module->basetable;
$field15->label= 'Qlik f3';
$field15->columntype = 'C(255)';
$field15->uitype = 1;
$field15->typeofdata = 'V~O';
$field15->quickcreate = 3;
$block4->addField($field15);

//vte_field 3
$field16 = new Vtiger_Field();
$field16->name = 'qlikiframe_value3';
$field16->table = $module->basetable;
$field16->label= 'Value 3';
$field16->columntype = 'C(255)';
$field16->uitype = 1;
$field16->typeofdata = 'V~O';
$field16->quickcreate = 3;
$field16->helpinfo = 'LBL_VALUE';
$block4->addField($field16);

//************ IV filtro ******************
//check 4
$field17 = new Vtiger_Field();
$field17->name = 'qlikiframe_check_4';
$field17->table = $module->basetable;
$field17->label= 'Check 4';
$field17->columntype = 'I(1)';
$field17->uitype = 56;
$field17->typeofdata = 'C~O';
$field17->quickcreate = 3;
$block5->addField($field17);

//sense field 4
$field18 = new Vtiger_Field();
$field18->name = 'qlikiframe_qlik_f4';
$field18->table = $module->basetable;
$field18->label= 'Qlik f4';
$field18->columntype = 'C(255)';
$field18->uitype = 1;
$field18->typeofdata = 'V~O';
$field18->quickcreate = 3;
$block5->addField($field18);

//vte_field 4
$field19 = new Vtiger_Field();
$field19->name = 'qlikiframe_value4';
$field19->table = $module->basetable;
$field19->label= 'Value 4';
$field19->columntype = 'C(255)';
$field19->uitype = 1;
$field19->typeofdata = 'V~O';
$field19->quickcreate = 3;
$field19->helpinfo = 'LBL_VALUE';
$block5->addField($field19);


//*********** V filtro ******************
//check 5
$field20 = new Vtiger_Field();
$field20->name = 'qlikiframe_check_5';
$field20->table = $module->basetable;
$field20->label= 'Check 5';
$field20->columntype = 'I(1)';
$field20->uitype = 56;
$field20->typeofdata = 'C~O';
$field20->quickcreate = 3;
$block6->addField($field20);

//sense field 5
$field21 = new Vtiger_Field();
$field21->name = 'qlikiframe_qlik_f5';
$field21->table = $module->basetable;
$field21->label= 'Qlik f5';
$field21->columntype = 'C(255)';
$field21->uitype = 1;
$field21->typeofdata = 'V~O';
$field21->quickcreate = 3;
$block6->addField($field21);

//vte_field 5
$field22 = new Vtiger_Field();
$field22->name = 'qlikiframe_value5';
$field22->table = $module->basetable;
$field22->label= 'Value 5';
$field22->columntype = 'C(255)';
$field22->uitype = 1;
$field22->typeofdata = 'V~O';
$field22->quickcreate = 3;
$field22->helpinfo = 'LBL_VALUE';
$block6->addField($field22);

//*********** VI filtro ******************
//check 6
$field23 = new Vtiger_Field();
$field23->name = 'qlikiframe_check_6';
$field23->table = $module->basetable;
$field23->label= 'Check 6';
$field23->columntype = 'I(1)';
$field23->uitype = 56;
$field23->typeofdata = 'C~O';
$field23->quickcreate = 3;
$block7->addField($field23);

//sense field 6
$field24 = new Vtiger_Field();
$field24->name = 'qlikiframe_qlik_f6';
$field24->table = $module->basetable;
$field24->label= 'Qlik f6';
$field24->columntype = 'C(255)';
$field24->uitype = 1;
$field24->typeofdata = 'V~O';
$field24->quickcreate = 3;
$block7->addField($field24);

//vte_field 6
$field25 = new Vtiger_Field();
$field25->name = 'qlikiframe_value6';
$field25->table = $module->basetable;
$field25->label= 'Value 6';
$field25->columntype = 'C(255)';
$field25->uitype = 1;
$field25->typeofdata = 'V~O';
$field25->quickcreate = 3;
$field25->helpinfo = 'LBL_VALUE';
$block7->addField($field25);


//*********** VII filtro ******************
//check 7
$field26 = new Vtiger_Field();
$field26->name = 'qlikiframe_check_7';
$field26->table = $module->basetable;
$field26->label= 'Check 7';
$field26->columntype = 'I(1)';
$field26->uitype = 56;
$field26->typeofdata = 'C~O';
$field26->quickcreate = 3;
$block8->addField($field26);

//sense field 7
$field27 = new Vtiger_Field();
$field27->name = 'qlikiframe_qlik_f7';
$field27->table = $module->basetable;
$field27->label= 'Qlik f7';
$field27->columntype = 'C(255)';
$field27->uitype = 1;
$field27->typeofdata = 'V~O';
$field27->quickcreate = 3;
$block8->addField($field27);

//vte_field 7
$field28 = new Vtiger_Field();
$field28->name = 'qlikiframe_value7';
$field28->table = $module->basetable;
$field28->label= 'Value 7';
$field28->columntype = 'C(255)';
$field28->uitype = 1;
$field28->typeofdata = 'V~O';
$field28->quickcreate = 3;
$field28->helpinfo = 'LBL_VALUE';
$block8->addField($field28);

/***********************************************************************/
// Mandatory fields
$field_ass = new Vtiger_Field();
$field_ass->name = 'assigned_user_id';
$field_ass->label = 'Assigned To';
$field_ass->table = $table_prefix.'_crmentity';
$field_ass->column = 'smownerid';
$field_ass->uitype = 53;
$field_ass->typeofdata = 'V~M';
$field_ass->quickcreate = 0;
$block1->addField($field_ass);

$field_cre = new Vtiger_Field();
$field_cre->name = 'createdtime';
$field_cre->label= 'Created Time';
$field_cre->table = $table_prefix.'_crmentity';
$field_cre->column = 'createdtime';
$field_cre->uitype = 70;
$field_cre->typeofdata = 'T~O';
$field_cre->displaytype= 2;
$block1->addField($field_cre);

$field_mod = new Vtiger_Field();
$field_mod->name = 'modifiedtime';
$field_mod->label= 'Modified Time';
$field_mod->table = $table_prefix.'_crmentity';
$field_mod->column = 'modifiedtime';
$field_mod->uitype = 70;
$field_mod->typeofdata = 'T~O';
$field_mod->displaytype= 2;
$block1->addField($field_mod);

// Create default custom filter (mandatory)
$filter1 = new Vtiger_Filter();
$filter1->name = 'All';
$filter1->isdefault = true;
$module->addFilter($filter1);

// Add fields to the filter created
$filter1->addField($field0,1)->addField($field1,2)->addField($field2,3)->addField($field4,4)->addField($field5,5)->addField($field6,6)->addField($field7,7);

// BLOCKS
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_INFORMATION', array('it_it'=>'Informazioni Generali Iframe',	'en_us'=>'General Informations'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER1', array('it_it'=>'Blocco Filtro 1','en_us'=>'Block Filter 1'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER2', array('it_it'=>'Blocco Filtro 2','en_us'=>'Block Filter 2'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER3', array('it_it'=>'Blocco Filtro 3','en_us'=>'Block Filter 3'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER4', array('it_it'=>'Blocco Filtro 4','en_us'=>'Block Filter 4'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER5', array('it_it'=>'Blocco Filtro 5','en_us'=>'Block Filter 5'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER6', array('it_it'=>'Blocco Filtro 6','en_us'=>'Block Filter 6'));
SDK::setLanguageEntries('QlikIframe', 'LBL_QLIKIFRAME_FILTER7', array('it_it'=>'Blocco Filtro 7','en_us'=>'Block Filter 7'));

//FIELDS
SDK::setLanguageEntries('QlikIframe', 'QlikIframe', 		array('it_it'=>'Analisi Qlik','en_us'=>'Qlik Analysis'));
SDK::setLanguageEntries('QlikIframe', 'SINGLE_QlikIframe',array('it_it'=>'Analisi Qlik','en_us'=>'Qlik Analysis'));
SDK::setLanguageEntries('QlikIframe', 'IFrame Name',	array('it_it'=>'Nome IFrame','en_us'=>'IFrame Name'));
SDK::setLanguageEntries('QlikIframe', 'Link', 			array('it_it'=>'URI','en_us'=>'URI'));
SDK::setLanguageEntries('QlikIframe', 'Modulo', 		array('it_it'=>'Modulo','en_us'=>'Module'));
SDK::setLanguageEntries('QlikIframe', 'Height', 		array('it_it'=>'Altezza','en_us'=>'Height'));
SDK::setLanguageEntries('QlikIframe', 'Analysis Type', array('it_it'=>'Tipo Analisi','en_us'=>'Analysis Type'));
SDK::setLanguageEntries('QlikIframe', 'Active', 	array('it_it'=>'Attivo','en_us'=>'Active'));
SDK::setLanguageEntries('QlikIframe', 'Position', 	array('it_it'=>'Posizionamento Iframe','en_us'=>'Positioning Iframe'));
SDK::setLanguageEntries('QlikIframe', 'Page Type', array('it_it'=>'Tipo Pagina','en_us'=>'Page Type'));

//CHECKBOX
SDK::setLanguageEntries('QlikIframe', 'Check 1', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 2', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 3', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 4', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 5', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 6', array('it_it'=>'','en_us'=>''));
SDK::setLanguageEntries('QlikIframe', 'Check 7', array('it_it'=>'','en_us'=>''));

//CAMPO QLIK
SDK::setLanguageEntries('QlikIframe', 'Qlik f1', array('it_it'=>'Campo Applicazione 1','en_us'=>'Application Field 1'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f2', array('it_it'=>'Campo Applicazione 2','en_us'=>'Application Field 2'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f3', array('it_it'=>'Campo Applicazione 3','en_us'=>'Application Field 3'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f4', array('it_it'=>'Campo Applicazione 4','en_us'=>'Application Field 4'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f5', array('it_it'=>'Campo Applicazione 5','en_us'=>'Application Field 5'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f6', array('it_it'=>'Campo Applicazione 6','en_us'=>'Application Field 6'));
SDK::setLanguageEntries('QlikIframe', 'Qlik f7', array('it_it'=>'Campo Applicazione 7','en_us'=>'Application Field 7'));

//CAMPO VALORE
SDK::setLanguageEntries('QlikIframe', 'Value 1', array('it_it'=>'Valore VTE 1','en_us'=>'VTE Value 1'));
SDK::setLanguageEntries('QlikIframe', 'Value 2', array('it_it'=>'Valore VTE 2','en_us'=>'VTE Value 2'));
SDK::setLanguageEntries('QlikIframe', 'Value 3', array('it_it'=>'Valore VTE 3','en_us'=>'VTE Value 3'));
SDK::setLanguageEntries('QlikIframe', 'Value 4', array('it_it'=>'Valore VTE 4','en_us'=>'VTE Value 4'));
SDK::setLanguageEntries('QlikIframe', 'Value 5', array('it_it'=>'Valore VTE 5','en_us'=>'VTE Value 5'));
SDK::setLanguageEntries('QlikIframe', 'Value 6', array('it_it'=>'Valore VTE 6','en_us'=>'VTE Value 6'));
SDK::setLanguageEntries('QlikIframe', 'Value 7', array('it_it'=>'Valore VTE 7','en_us'=>'VTE Value 7'));
SDK::setLanguageEntries('QlikIframe', 'LBL_VALUE',array('it_it'=>'Può assumere 4 forme: <br/> 1: valori separati da virgola, per filtro su valori; <br/>2: |$|nome_campo_vte|$| estrae il valore del campo che si sta visualizzando; <br/>3: |$d|nome_campo_vte|$d| per campi uitype10 viene estratto il valore e non l\'id; <br/> 4: |$r|nome_campo_uitype10.nome_campo_entit�_correlata|$r| per campi uitype 10 recupera il campo nome_campo_entità_correlata del modulo a cui riferisce <br/>	(es. |$r|accname.external_code|$r| ritorna il valore del campo external_code dell\'azienda relazionata al modulo selezionato)',
		'en_us'=>'Values separeted by comma; <br/>|$|vte_field_name|$| for vte field; <br/> |$d|vte_field_name|$d| retrieve description for fields with uitype 10;<br/> |$r|vte_field_uitype10.vte_field_name_entity_related|$r| retrieve value of field defined after \'.\' for entity related'));
		
$module->setDefaultSharing('Private');

$module->enableTools(Array('Import', 'Export'));
$module->disableTools('Merge');

// per aggiungere il supporto ai webservices
$module->initWebservice();

// changelog
$focus = CRMEntity::getInstance($module->name);
$focus->vtlib_handler($module->name, 'module.postinstall');

/*****************************************************************************************************************/
//														 														  /
//													POSTINSTALL													  /
//														 														  /
/*****************************************************************************************************************/
// ADD NEW HEADERSCRIPT TO MANAGE IFRAMES
Vtiger_Link::addLink($moduleInstance->id, 'HEADERSCRIPT', 'QlikIframe', 'modules/SDK/qlik/src/QlikIframe.js');

// ADD VIEW TO SET related_module field READONLY IN DETAILVIEW.
SDK::addView('QlikIframe','modules/SDK/qlik/src/ViewQlikIframe.php', 'constrain', 'continue');

//****************************************************************/
//						FIELDS FOR USER
//****************************************************************/
$fields = array();
$user_module=Vtiger_module::getInstance('Users');
$block7 = new Vtiger_Block();
$block7->label = 'LBL_USER_SENSE_OPTIONS';
$user_module->addBlock($block7);


$fields[] = array('module'=>'Users',
		'block'=>'LBL_USER_SENSE_OPTIONS',
		'name'=> 'qlikiframe_login_qlik',
		'label'=>'Login Qlik',
		'uitype'=>'1',
		'columntype'=>'C(255)',
		'typeofdata'=>'V~O',
		'quickcreate'=>'2');

$fields[] = array('module'=>'Users',
		'block'=>'LBL_USER_SENSE_OPTIONS',
		'name'=> 'qlikiframe_pwd_qlik',
		'label'=>'Pwd Qlik',
		'uitype'=>'1',
		'columntype'=>'C(255)',
		'typeofdata'=>'V~O',
		'quickcreate'=>'2');

include('modules/SDK/examples/fieldCreate.php');

SDK::setLanguageEntries('Users', 'LBL_USER_SENSE_OPTIONS', array('it_it'=>'Opzioni Sense','en_us'=>'Sense Options'));
SDK::setLanguageEntries('Users', 'Login Qlik', array('it_it'=>'Username Sense','en_us'=>'Username Sense'));
SDK::setLanguageEntries('Users', 'Pwd Qlik', array('it_it'=>'Folder Sense','en_us'=>'Folder Sense'));

SDK::setLanguageEntries('QlikIframe', 'LBL_TIP_URI',array('it_it'=>'SE il campo \'Tipo Analisi\' ha come valore \'Altro\', l\'URI deve avere come prefisso http:// (o https://)',
		'en_us'=>'IF \'Analysis Type\' field is \'Other\' the URI must has the prefix: http:// (or https://)'));

SDK::setLanguageEntries('QlikIframe', 'Altro',array('it_it'=>'Altro',
		'en_us'=>'Other'));
echo " \n\r RICORDA di AGGIUNGERE la VARIABILE \$qlik_proxy_url NEL FILE config.inc.php!!!!! ";
?>
