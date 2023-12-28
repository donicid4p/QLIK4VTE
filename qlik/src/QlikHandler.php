<?php 
class QlikHandler extends VTEventHandler {
	function handleEvent($eventName, $entityData) {
		global $log, $adb, $mod_strings;
		global $table_prefix;
		$moduleName = $entityData->getModuleName();
		$crmid = $entityData->getId();
		if($moduleName != 'QlikIframe'){
			return false;
		}
		if($eventName == 'vtiger.entity.aftersave') {
			$focus = $entityData->getData();
			if (isset($crmid)&& $crmid!=''){
				$query= "SELECT qlikiframe_module_related 
				FROM {$table_prefix}_qlikiframe
				WHERE qlikiframeid= {$crmid}";
				$result= $adb->query($query);
				if ($result && $adb->num_rows($result) > 0) {
					$state= $adb->query_result($result,0,'qlikiframe_module_related');			
					$updateField= "UPDATE {$table_prefix}_qlikiframe SET qlikiframe_txt_mod_name='$state' WHERE qlikiframeid= $crmid " ;
					$result=$adb->query($updateField);	
				}
			}
		}
	}
}
?>