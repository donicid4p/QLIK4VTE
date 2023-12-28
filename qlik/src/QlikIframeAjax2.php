<?php
//GESTISCE LA CREAZIONE DI IFRAME IN LISTVIEW PER VISUALIZZAZIONE NORMALE.
global $adb, $qlik_proxy_url, $table_prefix, $current_user;
$frame_perm=false; // per controllo su permesso del singolo record
$result = array();
//control that the module Iframe is active and permitted for the current user
if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){
	$res = $adb->pquery("SELECT *, ".$table_prefix."_qlikiframe.qlikiframeid AS qlikiframe_id_iframe,
			(SELECT debug FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1) AS debug
			FROM ".$table_prefix."_qlikiframe 
			INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
			INNER JOIN ".$table_prefix."_crmentity ON ".$table_prefix. "_qlikiframe.qlikiframeid = crmid AND deleted = 0 
			WHERE qlikiframe_page_type ='ListView' 
			AND qlikiframe_show_in_tab <>1 
			AND qlikiframe_module_related = ?",array($_REQUEST['mod'])); // aggiunta condizione per estrarre solo quelli che non si vogliono vedere in tab
	$newurl="";
	for($i=0;$i<$adb->num_rows($res);$i++){
		$riporta=true;//used to control if there are credentials for qlik sense
		//--------------------------------------------------------------------------------------//
		//									ATTENZIONE!!!
		//
		// QUALORA VENISSE MODIFICATO IL PROCESSO SOTTOSTANTE OCCORRE MODIFICARE ANCHE I FILE:
		// - QlikIframeAjax.php
		// - QlikIframeLoadCurrentTabAjax.php
		// - QlikIframeLoadHomeWidgetAjax.php
		// - QlikIframeModAjax.php
		// - QlikIframePreviewAjax.php
		//--------------------------------------------------------------------------------------//
		
		$current_id_iframe = $adb->query_result($res,$i, 'qlikiframe_id_iframe');
		if(isPermitted("QlikIframe","index",$current_id_iframe)!= "no"){
			$frame_perm=true;
			//control if the iframe is active. If is active, I put in turbolift the link
			$active=$adb->query_result($res,$i, 'qlikiframe_active');
			if($active <> 'No'){
				
				$type= $adb->query_result($res,$i, 'qlikiframe_type');
	
				//******************************** 	QLIKSENSE *********************************/
				//case when che type of analysis is qlik sense. Must concatenate link with other informations.
				
				$sense_url=$adb->query_result($res,$i, 'qlikiframe_link');
				$sense_url.='/entity/'.$current_user->id;

				if($type=='Qlik Sense'){
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
				}
				if($type <> 'Qlik Sense' || ($pwd && $username)){
				//if($pwd && $username){
					//for ($j=0; $j<5;$j++){
					for ($j=0; $j<7;$j++){
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
									
									// -- new case of type
									$field_entity=substr($value,0,4) == '|$r|' && substr($value,-4) == '|$r|';
									if($field_value||$field_descr||$field_entity) {}
									//if($field_value||$field_descr) {}
									
									else{ //ARE VALUES(for example, year (2010,2011 ...)
										if($type <> 'Qlik Sense'){
											if($parameters){
												$parameters .="&".$field."=".$value;
											}
											else{
												$parameters .="?".$field."=".$value;
											}
										}
										else{//Qlik Sense case
											$parameters .="&select=".$field.",".$value;
										}	
										
									}
								}// END !EMPTY FILTERS FIELDS
							}// END CHECK FILTER ACTIVE
						}// END FOR FOR FILTERS
						//$parameters .= "&identity=2";
						if (!empty($parameters)){
							$sense_url .= $parameters;
						}
					
						if($type <> 'Qlik Sense'){
							$newurl= $sense_url;
						}
						else{ //-- type = qliksense
							//- modificato da mauver per gestire una unica utenza di qlik 
							// prima della modifica -> $newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url);
							$newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url."&identity=2".$current_user->id);
						}
					}
					
					//die($newurl);
					//******************************** END	QLIKSENSE *********************************/
				/*}  commentato in quanto unificati i casi per poter utilizzare "Altro" in modo pi� efficace
				else{//at the moment there is only qliksense supported and Other, like URL .
					$newurl=$adb->query_result($res,$i,'w_link');
				}  end*/
				
				//if the iframe must show (there are credentials, add a element in the array,
				if($riporta){
					$result[]=array($adb->query_result($res,$i,'qlikiframe_name'),$newurl, $adb->query_result($res,$i,'qlikiframe_position'), $adb->query_result($res,$i, 'qlikiframe_height'),$adb->query_result($res,$i, 'debug') );
				}
			}// end IF MODULE IS ACTIVE
		}// close permitted record
	}// END FOR
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