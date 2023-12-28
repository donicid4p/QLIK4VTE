<?php
global $adb, $qlik_proxy_url, $table_prefix, $current_user;

//control that the module Iframe is active and permitted for the current user
if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){
	// aggiunto parametro in select per recuperare id dell'iframe per controllo su permession del record singolo.
	$res = $adb->query("SELECT * , ".$table_prefix."_qlikiframe.qlikiframeid as qlikiframe_id_iframe,
(SELECT debug FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1) AS debug
	FROM ".$table_prefix."_qlikiframe 
	INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
	INNER JOIN ".$table_prefix."_crmentity on ".$table_prefix."_qlikiframe.qlikiframeid = crmid AND deleted = 0 
	WHERE qlikiframe_page_type ='Modulo' AND qlikiframe_active <> 'No'");
	$newurl="";
	$result = array();
	for($i=0;$i<$adb->num_rows($res);$i++){
		
		$riporta=true;//used to control if there are credentials for qlik sense
		//--------------------------------------------------------------------------------------//
		//									ATTENZIONE!!!
		//
		// QUALORA VENISSE MODIFICATO IL PROCESSO SOTTOSTANTE OCCORRE MODIFICARE ANCHE I FILE:
		// - QlikIframeAjax.php
		// - QlikIframeAjax2.php
		// - QlikIframeLoadCurrentTabAjax.php
		// - QlikIframeLoadHomeWidgetAjax.php
		// - QlikIframePreviewAjax.php
		//--------------------------------------------------------------------------------------//
		
		//-- controllo se l'iframe (record) disponibile per l'utente corrente
		$current_id_iframe = $adb->query_result($res,$i,'qlikiframe_id_iframe');
		if(isPermitted("QlikIframe","index",$current_id_iframe)!= "no"){		
			$type= $adb->query_result($res,$i,'qlikiframe_type');
			
			//******************************** 	QLIKSENSE *********************************/
			//case when che type of analysis is qlik sense. Must concatenate link with other informations.
			if($type=='Qlik Sense'){
				$sense_url=$adb->query_result($res,$i,'qlikiframe_link');
			    
				//retrieve informations of user credentials for qlik.
				$other_info = $adb->query("SELECT qlikiframe_login_qlik, qlikiframe_pwd_qlik FROM ".$table_prefix."_users WHERE id= ".$current_user->id);
			
				$username= $adb->query_result($other_info,0,'qlikiframe_login_qlik');
				if($username){
					$username="?User=".$username;
				}
				else{
					$riporta=false;
				}
			
				$pwd= $adb->query_result($other_info,0,'qlikiframe_pwd_qlik');
				if($pwd){
					$pwd= $pwd;
					$pwd= "&Password=".$pwd;
				}
				else{
					$riporta=false;
				}
			
				$parameters="";
			
				if($pwd && $username){
					for ($j=0; $j<5;$j++){
						$k= $j+1;
						$check_values=recover_check_value($res,$i,$k);
						$val="1";
						if($check_values==$val){//the filter is set, recover che sense field and value
							$field= recover_qlik_field($res,$i,$k);
							$value= recover_vte_value($res,$i,$k);
								
							//control if che textbox are filled. In this case, control che field value, if is a sequence of values or a vte field
							if(!empty($field)&&!empty($value)){
								//IS A VTE FIELD
								$value=trim($value);
								$field_value=substr($value,0,3) == '|$|' && substr($value,-3) == '|$|'; //if value is a field and want take id (if field is uitype 10) or field value
								$field_descr=substr($value,0,4) == '|$d|' && substr($value,-4) == '|$d|'; //if value is a field and want take the identifier field for id (if field is uitype 10)
			
								if($field_value||$field_descr) {}
								else{ //ARE VALUES(for example, year (2010,2011 ...)
									$parameters .="&select=".$field.",".$value;
										
								}
							}
						}
					}
					//$parameters .= "&identity=2";
					if (!empty($parameters)){
						$sense_url .= $parameters;
					}
					//- modificato per gestire una unica utenza di qlik 
							// prima della modifica -> $newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url);
					$newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url."&identity=2".$current_user->id);
				}
				//******************************** END	QLIKSENSE *********************************/
			}
			else{//at the moment there is only qliksense supported and Other, like URL .
				$newurl=$adb->query_result($res,$i,'qlikiframe_link');
			}
				
			//if the iframe must show (there are credentials, add a element in the array,
			if($riporta){
				$result[]=array($adb->query_result($res,$i,'qlikiframe_name'),$newurl, $adb->query_result($res,$i,'qlikiframe_height'), $adb->query_result($res,$i,'qlikiframeid'),$adb->query_result($res,$i,'debug'));
			}
		} // -- chiude controllo su record accessibile all'utente corrente
	}//end FOR
	
	echo Zend_Json::encode($result);

}
/*********************** FUNCTIONS **************************/
function recover_check_value($res,$row,$index){
	global $adb;
	return $adb->query_result($res,$row,'qlikiframe_check_'.$index);
}

function recover_qlik_field($res,$row,$index){
	global $adb;
	return $adb->query_result($res,$row,'qlikiframe_qlik_f'.$index);
}

function recover_vte_value($res,$row,$index){
	global $adb;
	return $adb->query_result($res,$row,'qlikiframe_value'.$index);
}
?>