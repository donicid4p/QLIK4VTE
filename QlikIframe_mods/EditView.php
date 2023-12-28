<?php
/***************************************************************************************
 * The contents of this file are subject to the CRMVILLAGE.BIZ VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is:  CRMVILLAGE.BIZ VTECRM
 * The Initial Developer of the Original Code is CRMVILLAGE.BIZ.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
require_once 'modules/VteCore/EditView.php';	//crmv@30447

// crmv@64542

global $currentModule;

$templates = array(
	'inventory' => 'Inventory/InventoryEditView.tpl',
	'standard' => 'salesEditView.tpl',
);

$templateMode = isInventoryModule($currentModule) ? 'inventory' : 'standard';



$smarty->display($templates[$templateMode]);
