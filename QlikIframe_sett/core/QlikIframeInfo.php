<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 *
 ********************************************************************************/


class QlikIframeInfo {
	// id of this conf record
	var $conf_id = false;
	// name of this conf
	var $confname=false;
	
	var $endpoint = false;
var $QRSCertfile = false;
var $QRSCertkeyfile = false;
var $QRSCertkeyfilePassword = false;
var $active = false;
var $isValid = false;
var $QRSurl = false;
var $debug = false;

	function __construct($confname, $initialize=true) {
		if($initialize && $confname) $this->initialize($confname);
		
		$this->initializeDefaultValues(); // crmv@179550
	}
	

	function initializeDefaultValues() {
		// Default values in creation mode
		if (empty($this->conf_id)) {
			$this->searchfor = 'UNSEEN';
			$this->markas = 'SEEN';
		}
	}

	/**
	 * Get conf information as map
	 */
	function getAsMap() {
		$infomap = Array();
		$keys = Array('conf_id', 'confname','QRSurl', 'active', 'isValid' ,'endpoint', 'QRSCertfile', 'QRSCertkeyfile', 'QRSCertkeyfilePassword', 'debug'); //crmv@2043m crmv@178441
		foreach($keys as $key) {
			$infomap[$key] = $this->$key;
		}
		return $infomap;
	}

	/**
	 * Initialize this instance.
	 */
	function initialize($confname) {
		global $adb,$table_prefix;
		//var_dump($confname);
		$result = $adb->pquery("SELECT * FROM ".$table_prefix."_qlikiframeconfs WHERE confname=? ORDER BY conf_id DESC LIMIT 1", Array($confname));



		if($adb->num_rows($result)) {
$row = $adb->query_result_rowdata($result, 0);


$this->conf_id = $row['conf_id'];
$this->confname = $row['confname'];
$this->endpoint = $row['endpoint'];
$this->QRSCertfile = $row['qrscertfile'];
$this->QRSCertkeyfile = $row['qrscertkeyfile'];
$this->QRSCertkeyfilePassword = $row['qrscertkeyfilepassword'];
$this->active = $row['active'];
$this->isValid = $row['active'];
$this->QRSurl = $row['qrsurl'];
$this->debug = $row['debug'];



		}

	}

function disableAll() {
global $adb,$table_prefix;
$adb->pquery("UPDATE ".$table_prefix."_qlikiframeconfs SET active=0", Array());


}

function save_files() {
global $adb,$table_prefix, $_FILES, $_REQUEST;

    $uploadDirectory = "storage/qlik_vte/";


if (!file_exists($uploadDirectory)) {

	mkdir($uploadDirectory, 0777, true);
}


    $tempFilePath = $_FILES['QRSCertfile']['tmp_name'];
    $originalFileName = $_FILES['QRSCertfile']['name'];
    $uniqueFileName = uniqid() . '_' . $originalFileName;
	$finalFilePath = $uploadDirectory . $uniqueFileName;
	$m = move_uploaded_file($tempFilePath, $finalFilePath);
	if ($m == 1) {
$this->QRSCertfile  = $finalFilePath;
	} else {
		$this->QRSCertfile  = !empty($this->QRSCertfile) ? $this->QRSCertfile : " ";
	}

    $tempFilePath = $_FILES['QRSCertkeyfile']['tmp_name'];
    $originalFileName = $_FILES['QRSCertkeyfile']['name'];
    $uniqueFileName = uniqid() . '_' . $originalFileName;
	$finalFilePath = $uploadDirectory . $uniqueFileName;
	$m = move_uploaded_file($tempFilePath, $finalFilePath);
	if ($m == 1) {
$this->QRSCertkeyfile  = $finalFilePath;
	} else {
		$this->QRSCertkeyfile  = !empty($this->QRSCertkeyfile) ? $this->QRSCertkeyfile : " ";
	}



	}






	function update($otherInstance) {

		$confChanged = false;

		if($this->confname != $otherInstance->confname || $this->endpoint != $otherInstance->endpoint) {
			$confChanged = true;

		}

		$this->confname    = $otherInstance->confname;
		$this->conf_id = $otherInstance->conf_id;


		$this->endpoint  = $otherInstance->endpoint;
$this->QRSurl  = $otherInstance->QRSurl;


		$this->save_files();


		/*$this->QRSCertfile  = $otherInstance->QRSCertfile;
		$this->QRSCertkeyfile  = $otherInstance->QRSCertkeyfile;
*/
		$this->QRSCertkeyfilePassword  = $otherInstance->QRSCertkeyfilePassword;

		$this->active  = $otherInstance->active;
$this->debug  = $otherInstance->debug;

//solo una configurazione può essere attiva
		if ($this->active == 1) {

$this->disableAll();

		}





		global $adb,$table_prefix;
		if($this->conf_id) { // This record exists in the database
			$adb->pquery("UPDATE ".$table_prefix."_qlikiframeconfs SET confname=?, endpoint=?, QRSCertfile=?, QRSCertkeyfile=?, QRSCertkeyfilePassword=?, active=?, QRSurl=?, debug=? WHERE conf_id=?", Array($this->confname, $this->endpoint, $this->QRSCertfile, $this->QRSCertkeyfile, $this->QRSCertkeyfilePassword, $this->active,$this->QRSurl,$this->debug, $this->conf_id));
		} else {
			//crmv@16212
			$this->conf_id = $adb->getUniqueID($table_prefix.'_qlikiframeconfs');

 $adb->pquery("INSERT INTO ".$table_prefix."_qlikiframeconfs 
(conf_id, confname, endpoint, QRSCertfile, QRSCertkeyfile, QRSCertkeyfilePassword, active,QRSurl, debug) VALUES 
(?,?,?,?,?,?,?,?,?)",
 Array($this->conf_id, $this->confname, $this->endpoint, $this->QRSCertfile, $this->QRSCertkeyfile, $this->QRSCertkeyfilePassword, $this->active, $this->QRSurl,$this->debug));

		}




		return $this->conf_id;
	}




	function delete() {
		global $adb,$table_prefix;
		

		if($this->conf_id) {
			$tables = Array(
				$table_prefix.'_qlikiframeconfs'
			);
			foreach($tables as $table) {
				$adb->pquery("DELETE FROM $table WHERE conf_id=?", Array($this->conf_id));
			}

		}
	}
	

	static function listAll($idstart='',$idend='') {
		$confs = array();
		
		global $adb,$table_prefix;
		if ($idstart != '') {
			if ($idend != '') {
				$result = $adb->pquery("SELECT confname FROM ".$table_prefix."_qlikiframeconfs where conf_id >= ? and conf_id < ? ", array($idstart,$idend));
			} else {
				$result = $adb->pquery("SELECT confname FROM ".$table_prefix."_qlikiframeconfs where conf_id >= ? ", array($idstart));
			}
		} else {
			$result = $adb->query("SELECT confname FROM ".$table_prefix."_qlikiframeconfs");
		}
		if ($result && $adb->num_rows($result)) {
			while($resultrow = $adb->fetch_array($result)) {

				$confs[] = new self( decode_html($resultrow['confname'] ));
			}
		}
		return $confs;
	}
	// crmv@115308e
}
