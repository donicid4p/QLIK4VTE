<?php
/************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by CRMVILLAGE.BIZ are Copyright (C) CRMVILLAGE.BIZ.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class QlikIframe extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name;
	var $table_index= 'qlikiframeid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array();

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array();

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array();

	/**
	 * Mandatory for Listing (Related listview)
	 */

	var $list_fields = Array ();
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'IFrame Name'=>'qlikiframe_name',
		'Link'=> 'qlikiframe_link',
		'Modulo'=> 'qlikiframe_module_related',
		'Height'=> 'qlikiframe_height'
		
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'qlikiframe_name';

	// For Popup listview and UI type support
	var $search_fields = Array();
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'IFrame Name'=> 'qlikiframe_name'
	);

	// For Popup window record selection
	var $popup_fields = Array('qlikiframe_name');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'qlikiframe_name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'qlikiframe_name';

	// Required Information for enabling Import feature
	var $required_fields = Array('qlikiframe_name'=>1);

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'qlikiframe_name';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vte_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'qlikiframe_name');
	//crmv@10759
	var $search_base_field = 'qlikiframe_name';
	//crmv@10759 e

	function __construct() {
		global $log, $table_prefix; // crmv@64542
		parent::__construct(); // crmv@37004
		$this->table_name = $table_prefix.'_qlikiframe';
		$this->customFieldTable = Array($table_prefix.'_qlikiframecf', 'qlikiframeid');
		$this->entity_table = $table_prefix."_crmentity";
		$this->tab_name = Array($table_prefix.'_crmentity', $table_prefix.'_qlikiframe', $table_prefix.'_qlikiframecf');
		$this->tab_name_index = Array(
			$table_prefix.'_crmentity' => 'crmid',
			$table_prefix.'_qlikiframe'   => 'qlikiframeid',
			$table_prefix.'_qlikiframecf' => 'qlikiframeid'
		);
		$this->list_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'IFrame Name'=>Array($table_prefix.'_qlikiframe','qlikiframe_name'),
			'Link'=> Array($table_prefix.'_qlikiframe','qlikiframe_link'),
			'Modulo'=>Array($table_prefix.'_qlikiframe', 'qlikiframe_module_related'),
			'Height'=>Array($table_prefix.'_qlikiframe', 'qlikiframe_height'),
		);
		$this->search_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'IFrame Name'=> Array($table_prefix.'_qlikiframe', 'w_qlikiframe_name')
		);
		$this->column_fields = getColumnFields(get_class()); // crmv@64542
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	/*
	// moved in CRMEntity
	function getSortOrder() { }
	function getOrderBy() { }
	*/

	// crmv@64542
	function save_module($module) {
		global $adb,$table_prefix;
		
		// save the products block
		if (!empty($module) && isInventoryModule($module)) {
			//in ajax save we should not call this function, because this will delete all the existing product values
			if($_REQUEST['action'] != "{$module}Ajax" && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave') {
				$InventoryUtils = InventoryUtils::getInstance();
				//Based on the total Number of rows we will save the product relationship with this entity
				$InventoryUtils->saveInventoryProductDetails($this, $module);
			}

			// Update the currency id and the conversion rate for the module
			$update_query = "UPDATE {$this->table_name} SET currency_id=?, conversion_rate=? WHERE {$this->table_index} = ?";
			$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
			$adb->pquery($update_query, $update_params);
		}
		
		// You can add more options here
		// ...
	}
	// crmv@64542e

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord) {
		// $srcrecord could be empty
	}

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where,$oCustomView,$viewId)	//crmv@31775
	{
		global $current_user,$table_prefix;
		$thismodule = $_REQUEST['module'];

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery($thismodule, "detail_view");

		$fields_list = getFieldsListFromQuery($sql);

		$query = 
			"SELECT $fields_list, {$table_prefix}_users.user_name AS user_name
			FROM {$table_prefix}_crmentity 
			INNER JOIN $this->table_name ON {$table_prefix}_crmentity.crmid=$this->table_name.$this->table_index";
		
		// crmv@96636
		foreach ($this->tab_name as $tab) {
			if ($tab == "{$table_prefix}_crmentity" || $tab == $this->table_name) continue;
			if ($this->customFieldTable && $tab == $this->customFieldTable[0]) continue;
			$index = $this->tab_name_index[$tab];
			if ($index) {
				$query .= " INNER JOIN {$tab} ON {$tab}.{$index} = {$this->table_name}.{$this->table_index}";
			}
		}
		// crmv@96636e

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}

		$query .= " LEFT JOIN {$table_prefix}_groups ON {$table_prefix}_groups.groupid = {$table_prefix}_crmentity.smownerid";
		$query .= " LEFT JOIN {$table_prefix}_users ON {$table_prefix}_crmentity.smownerid = {$table_prefix}_users.id and {$table_prefix}_users.status='Active'";

		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM {$table_prefix}_field" .
				" INNER JOIN {$table_prefix}_fieldmodulerel ON {$table_prefix}_fieldmodulerel.fieldid = {$table_prefix}_field.fieldid" .
				" WHERE uitype='10' AND {$table_prefix}_fieldmodulerel.module=?", array($thismodule));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');

			$other = CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);

			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
		}

		//crmv@31775
		$reportFilter = $oCustomView->getReportFilter($viewId);
		if ($reportFilter) {
			$tableNameTmp = $oCustomView->getReportFilterTableName($reportFilter,$current_user->id);
			$query .= " INNER JOIN $tableNameTmp ON $tableNameTmp.id = {$table_prefix}_crmentity.crmid";
		}
		//crmv@31775e

		//crmv@58099
		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " {$table_prefix}_crmentity.deleted = 0 ";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";
		
		$query = $this->listQueryNonAdminChange($query, $thismodule);
		//crmv@58099e
		
		return $query;
	}

	/**
	 * Initialize this instance for importing.
	 */
	function initImport($module) {
		$this->db = PearDatabase::getInstance();
		$this->initImportableFields($module);
	}

	/**
	 * Create list query to be shown at the last step of the import.
	 * Called From: modules/Import/UserLastImport.php
	 */
	function create_import_query($module) {
		global $current_user,$table_prefix;
		$query = "SELECT {$table_prefix}_crmentity.crmid, case when ({$table_prefix}_users.user_name is not null) then {$table_prefix}_users.user_name else {$table_prefix}_groups.groupname end as user_name, $this->table_name.* FROM $this->table_name
			INNER JOIN {$table_prefix}_crmentity ON {$table_prefix}_crmentity.crmid = $this->table_name.$this->table_index
			LEFT JOIN {$table_prefix}_users_last_import ON {$table_prefix}_users_last_import.bean_id={$table_prefix}_crmentity.crmid
			LEFT JOIN {$table_prefix}_users ON {$table_prefix}_users.id = {$table_prefix}_crmentity.smownerid
			LEFT JOIN {$table_prefix}_groups ON {$table_prefix}_groups.groupid = {$table_prefix}_crmentity.smownerid
			WHERE {$table_prefix}_users_last_import.assigned_user_id='$current_user->id'
			AND {$table_prefix}_users_last_import.bean_type='$module'
			AND {$table_prefix}_users_last_import.deleted=0";
		return $query;
	}

	/**
	 * Delete the last imported records.
	 */
	function undo_import($module, $user_id) {
		global $adb,$table_prefix;
		$count = 0;
		$query1 = "select bean_id from {$table_prefix}_users_last_import where assigned_user_id=? AND bean_type='$module' AND deleted=0";
		$result1 = $adb->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());
		while ( $row1 = $adb->fetchByAssoc($result1))
		{
			$query2 = "update {$table_prefix}_crmentity set deleted=1 where crmid=?";
			$result2 = $adb->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
			$count++;
		}
		return $count;
	}

	/**
	 * Transform the value while exporting
	 */
	function transform_export_value($key, $value) {
		return parent::transform_export_value($key, $value);
	}

	/**
	 * Function which will set the assigned user id for import record.
	 */
	function set_import_assigned_user()
	{
		global $current_user, $adb,$table_prefix;
		$record_user = $this->column_fields["assigned_user_id"];

		if($record_user != $current_user->id){
			$sqlresult = $adb->pquery("select id from {$table_prefix}_users where id = ? union select groupid as id from {$table_prefix}_groups where groupid = ?", array($record_user, $record_user));
			if($this->db->num_rows($sqlresult)!= 1) {
				$this->column_fields["assigned_user_id"] = $current_user->id;
			} else {
				$row = $adb->fetchByAssoc($sqlresult, -1, false);
				if (isset($row['id']) && $row['id'] != -1) {
					$this->column_fields["assigned_user_id"] = $row['id'];
				} else {
					$this->column_fields["assigned_user_id"] = $current_user->id;
				}
			}
		}
	}

	/**
	 * Function which will give the basic query to find duplicates
	 */
	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
	global $table_prefix;
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, {$table_prefix}_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= "	INNER JOIN {$table_prefix}_crmentity ON {$table_prefix}_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
		}
		$from_clause .= " LEFT JOIN {$table_prefix}_users ON {$table_prefix}_users.id = {$table_prefix}_crmentity.smownerid
						LEFT JOIN {$table_prefix}_groups ON {$table_prefix}_groups.groupid = {$table_prefix}_crmentity.smownerid";

		$where_clause = "	WHERE {$table_prefix}_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);

		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM  $this->table_name AS t " .
				" INNER JOIN {$table_prefix}_crmentity AS crm ON crm.crmid = t.".$this->table_index;
			// Consider custom table join as well.
			if(isset($this->customFieldTable)) {
				$sub_query .= " INNER JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $select_cols HAVING COUNT(*)>1";
		} else {
			$sub_query = "SELECT $table_cols $from_clause $where_clause GROUP BY $table_cols HAVING COUNT(*)>1";
		}

		$query = $select_clause . $from_clause .
					" LEFT JOIN {$table_prefix}_users_last_import ON {$table_prefix}_users_last_import.bean_id=" . $this->table_name .".".$this->table_index .
					" INNER JOIN (" . $sub_query . ") temp ON ".get_on_clause($field_values,$ui_type_arr,$module) .
					$where_clause .
					" ORDER BY $table_cols,". $this->table_name .".".$this->table_index ." ASC";

		return $query;
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		global $adb,$table_prefix;
		if($event_type == 'module.postinstall') {

			//crmv@29617
			$result = $adb->pquery('SELECT isentitytype FROM '.$table_prefix.'_tab WHERE name = ?',array($modulename));
			if ($result && $adb->num_rows($result) > 0 && $adb->query_result($result,0,'isentitytype') == '1') {

				$ModCommentsModuleInstance = Vtiger_Module::getInstance('ModComments');
				if ($ModCommentsModuleInstance) {
					$ModCommentsFocus = CRMEntity::getInstance('ModComments');
					$ModCommentsFocus->addWidgetTo($modulename);
				}

				$ChangeLogModuleInstance = Vtiger_Module::getInstance('ChangeLog');
				if ($ChangeLogModuleInstance) {
					$ChangeLogFocus = CRMEntity::getInstance('ChangeLog');
					$ChangeLogFocus->enableWidget($modulename);
				}

				$ModNotificationsModuleInstance = Vtiger_Module::getInstance('ModNotifications');
				if ($ModNotificationsModuleInstance) {
					$ModNotificationsCommonFocus = CRMEntity::getInstance('ModNotifications');
					$ModNotificationsCommonFocus->addWidgetTo($modulename);
				}

				$MyNotesModuleInstance = Vtiger_Module::getInstance('MyNotes');
				if ($MyNotesModuleInstance) {
					$MyNotesCommonFocus = CRMEntity::getInstance('MyNotes');
					$MyNotesCommonFocus->addWidgetTo($modulename);
				}
			}
			//crmv@29617e
			
			//crmv@92272
			$ProcessesFocus = CRMEntity::getInstance('Processes');
			$ProcessesFocus->enable($modulename);
			//crmv@92272e
			
			//crmv@105882 - initialize home for all users
			require_once('include/utils/ModuleHomeView.php');
			$MHW = ModuleHomeView::install($modulename);
			//crmv@105882e

		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	/*
	function save_related_module($module, $crmid, $with_module, $with_crmid) {
		parent::save_related_module($module, $crmid, $with_module, $with_crmid);
		//...
	}
	*/

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }
}
?>