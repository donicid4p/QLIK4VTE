<?php
//UTILIZZATO PER LA DETAILVIEW E PER LA VISUALIZZAZIONE IN TAB PER DETAILVIEW DI UN MODULO.

global $adb, $qlik_proxy_url, $table_prefix, $current_user;

$debug = $adb->query("SELECT debug FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1");
$debug = $adb->query_result($debug,0,'debug');

$res=''; 
$result = array();
$frame_perm = false; // aggiunto per controllo su singolo iframe
//control that the module Iframe is active and permitted for the current user
if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){
	if(isset($_REQUEST['qlikiframe_modid'])){  
		$stuffid = $_REQUEST['qlikiframe_modid'];
		$qlikiframe_type='modulehome';
		if(isset($_REQUEST['qlikiframe_type'])) $qlikiframe_type='panels';
		$q_iframe = $adb->pquery("SELECT *

			FROM qlikiframe_analysis_tab
			INNER JOIN ".$table_prefix."_".$qlikiframe_type."
			WHERE qlikiframe_id = ? AND qlikiframe_type = ?
			AND (qlikiframeid IS NOT NULL OR TRIM(qlikiframeid)<>'' OR qlikiframeid > 0)",array($stuffid,$qlikiframe_type));
		//se il tab contiene un iframe, allora proseguo
		if(isset($q_iframe) && $adb->num_rows($q_iframe)>0){
			$iframeid = $adb->query_result($q_iframe,0,'qlikiframeid');
			$res =$adb->pquery("SELECT * , ifr.qlikiframeid as qlikiframe_id_iframe

			FROM ".$table_prefix."_qlikiframe AS ifr
			INNER JOIN ".$table_prefix."_qlikiframecf AS cf ON ifr.qlikiframeid=cf.qlikiframeid
			INNER JOIN ".$table_prefix."_crmentity ON crmid=ifr.qlikiframeid AND deleted =0
			WHERE ifr.qlikiframeid = ?
			AND qlikiframe_module_related =?
			AND qlikiframe_page_type = ?
			AND qlikiframe_show_in_tab = 1", array($iframeid,$_REQUEST['mod'],$_REQUEST['qlikiframe_page']));
		
		}
		else{
			$result[]='ERROR';
			$result[]=getTranslatedString('GENERAL_ERROR_QLIK','APP_STRINGS');
		}
	}
	else{
		// modificata query aggiungendo controllo sul campo show in tab
		$res = $adb->pquery("SELECT *, ". $table_prefix."_qlikiframe.qlikiframeid as qlikiframe_id_iframe 
		FROM ".$table_prefix."_qlikiframe 
		INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
		INNER JOIN ".$table_prefix."_crmentity ON ".$table_prefix. "_qlikiframe.qlikiframeid = crmid AND deleted = 0 
		WHERE qlikiframe_page_type ='DetailView' 
		AND qlikiframe_show_in_tab <>1
		AND qlikiframe_module_related = ?",array($_REQUEST['mod']));
	}
	$newurl="";
	
	$count_row = (isset($res) && trim($res)!='')? $adb->num_rows($res) : 0;
	for($i=0;$i<$count_row;$i++){ // utilizzata variabile count_row invece di num_rows direttamente
		$riporta=true;//used to control if there are credentials for qlik sense
		
		//--------------------------------------------------------------------------------------//
		//									ATTENZIONE!!!
		//
		// QUALORA VENISSE MODIFICATO IL PROCESSO SOTTOSTANTE AD ECCEZIONE
		// DELLA PORZIONE DI CODICE CONTENUTA TRA I TAG "DETAILVIEW_IFRAME"
		// OCCORRE MODIFICARE ANCHE I FILE:
		// - QlikIframeAjax2.php
		// - QlikIframeLoadCurrentTabAjax.php
		// - QlikIframeLoadHomeWidgetAjax.php
		// - QlikIframeModAjax.php
		// - QlikIframePreviewAjax.php
		//--------------------------------------------------------------------------------------//
		
		// -- controllo se permesso l'accessso a quel record
		$current_id_iframe = $adb->query_result($res,$i,'qlikiframe_id_iframe');
		if(isPermitted("QlikIframe","index",$current_id_iframe)!= "no"){
			$frame_perm=true;
			//control if the iframe is active. If is active, I put in turbolift the link
			$active=$adb->query_result($res,$i,'qlikiframe_active');
			if($active <> 'No'){
				$type= $adb->query_result($res,$i,'qlikiframe_type');
				
				//******************************** 	QLIKSENSE *********************************/
				//case when che type of analysis is qlik sense. Must concatenate link with other informations.
				$appl_url=$adb->query_result($res,$i,'qlikiframe_link');
					if($type =='Qlik Sense'){
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
							$pwd= $pwd; // md5($pwd); 
							$pwd= "&Password=".$pwd;
						}
						else{
							$riporta=false;
						}
					}	
						$parameters="";
					
					if($type<> 'Qlik Sense' ||($pwd && $username)){
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
									
									$field_other=substr($value,0,4) == '|$r|' && substr($value,-4) == '|$r|'; //if value is a field from other entity(if field is uitype 10)
									//if($field_value||$field_descr) {//is a vte_field
									//ATTENZIONE: SE VIENE MODIFICATO IL COMPORTAMENTO DI QUESTA PARTE,
									//NON OCCORRE MODIFICARE I FILE SOPRA DESCRITTI
									//DETAILVIEW_IFRAME START
									if($field_value||$field_descr||$field_other) {//is a vte_field
	
										$vte_field_final="";
										if($field_value){//want value or id
											$array_vte_field= explode("|$|", $value);
											$vte_field=$array_vte_field[1];//here there is the vte field 
					
											$focus = CRMEntity::getInstance($_REQUEST['mod']);
											$focus->retrieve_entity_info($_REQUEST['record'],$_REQUEST['mod']);
											$vte_field_final=$focus->column_fields[$vte_field];			
										}
										elseif($field_descr){ //want description of uitype10 field
											$array_vte_field_10= explode('|$d|', $value);
											$vte_field_10=$array_vte_field_10[1];
											//retrieve information about value of uitype 10 field
											$focus = CRMEntity::getInstance($_REQUEST['mod']);
											$focus->retrieve_entity_info($_REQUEST['record'],$_REQUEST['mod']);
											$id_uitype_10=$focus->column_fields[$vte_field_10]; //value of uitype 10
											
											//retrieve information about module related
											$module_related_query = "SELECT setype, tabid, entityidfield
													FROM ".$table_prefix."_crmentity
													INNER JOIN ".$table_prefix."_entityname on setype = modulename
													WHERE crmid = ".$id_uitype_10;
											$module_related_result = $adb->query($module_related_query);
											
											if($module_related_result && $adb->num_rows($module_related_result)>0){
												$module_related = $adb->query_result($module_related_result,0,'setype');
												$module_related_id = $adb->query_result($module_related_result,0,'tabid');
												$identity_field =  $adb->query_result($module_related_result,0,'entityidfield');
											
												$vte_field_final = retrieve_description_uitype10($module_related,$id_uitype_10);//esqogito_0602 portato nella funzione che invoca, il codice presente precedentemente
											}
										} //end elseif($field_descr)
										else{
											$array_vte_field_10= explode('|$r|', $value);
											$array_params = explode('.',$array_vte_field_10[1]);
											$vte_field_10= $array_params[0]; //uitype 10 in module name of field
											$field_related = $array_params[1]; //field from entity that refere uitype 10
											
											//retrieve information about value of uitype 10 field
											$focus = CRMEntity::getInstance($_REQUEST['mod']);
											$focus->retrieve_entity_info($_REQUEST['record'],$_REQUEST['mod']);
											$id_uitype_10=$focus->column_fields[$vte_field_10]; //value of uitype 10
											
											//retrieve information about module related
											$module_related_query2 ="SELECT setype, tabid, entityidfield 
													FROM ".$table_prefix."_crmentity 
													INNER JOIN ".$table_prefix."_entityname on setype = modulename 
													WHERE crmid = ".$id_uitype_10;
											$module_related_result2 = $adb->query($module_related_query2);
											if($module_related_result2 && $adb->num_rows($module_related_result2)>0){
												$module_related = $adb->query_result($module_related_result2,0,'setype');
												$module_related_id = $adb->query_result($module_related_result2,0,'tabid');
												$identity_field =  $adb->query_result($module_related_result2,0,'entityidfield');
											
											//retrieve value of field about entity related with module for current iframe
												$query_information = "SELECT tablename, uitype 
														FROM ".$table_prefix."_field 
														WHERE tabid = ".$module_related_id." 
														AND fieldname = '".$field_related."'";
												$information_result = $adb->query($query_information);
												if($information_result && $adb->num_rows($information_result) > 0){
													$field_uitype = $adb->query_result($information_result,0,'uitype');
													$from_table = $adb->query_result($information_result,0,'tablename');
													
													$value_related_query = "SELECT ".$field_related." FROM ".$from_table." WHERE ".$identity_field." =".$id_uitype_10;
													$value_related_result = $adb->query($value_related_query);
													if($value_related_result && $adb->num_rows($value_related_result)> 0){
														$vte_field_final = $adb->query_result($value_related_result,0,$field_related);
														
														//if uitype = 10, must retrieve description  using function created
														if($field_uitype == 10){
															$vte_field_final = retrieve_description_uitype10($module_related,$vte_field_final);
														}
													}
												}
												
											}//END MODULE RELATED QUERY HAS RESULTS
											
										}// END NEW TYPE |$r|
										//DETAILVIEW_IFRAME END
										//concatenate parameter
										if($type <> 'Qlik Sense'){
											if($parameters){
												$parameters .="&".$field."=".urlencode($vte_field_final);
											}
											else{
												$parameters .="?".$field."=".urlencode($vte_field_final);
											}
										}
										else{//Qlik Sense case
											$parameters .="&select=".$field.",".urlencode($vte_field_final);
										}
										
									}
									else{ //ARE VALUES(for example, year (2010,2011 ...)
										if($type <> 'Qlik Sense'){
											if($parameters){
												$parameters .="&".$field."=".urlencode($value);
											}
											else{
												$parameters .="?".$field."=".urlencode($value);
											}
										}
										else{
											$parameters .="&select=".$field.",".urlencode($value);
										}
									}
								} //END fields of filters filled
							}//END if there are active filters
						}// END
					    //$parameters .= "&identity=2";
						if (!empty($parameters)){
							$appl_url .= $parameters;
						}
	
						if($type <> 'Qlik Sense'){
	
							$newurl=$appl_url;
						}
						else{
							//modificato da mauver per gestire una unica utenza di qlik 
							// prima della modifica -> $newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($appl_url);
							$newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($appl_url."&identity=2".$current_user->id);
							 
						}
					}
					//******************************** END *********************************/
	
				//if the iframe must show (there are credentials, add a element in the array, 
				if($riporta){
					if(isset($_REQUEST['qlikiframe_modid'])) 
						$result[]='CORRECT';
					$result[]=array($adb->query_result($res,$i, 'qlikiframe_name'),$newurl, $adb->query_result($res,$i,'qlikiframe_position'), $adb->query_result($res,$i,'qlikiframe_height'), $current_user->column_fields['qlikiframe_login_qlik'],$debug );
					//$result[] = $adb->query_result($res,$i,'debug');
				}
				// -- credenziali errate
				else{
					if(isset($_REQUEST['qlikiframe_modid'])){
						$result[]='ERROR';
						$result[]=getTranslatedString('CREDENTIALS_WRONG','APP_STRINGS');
						//messaggio di errore: credenziali non valide per l'analisi selezionata. Controllare le impostazioni utente
					}
				}
			}//end active module Iframe
			
			//-- analisi nn attiva
			else{
				//messaggio di errore: analisi non attiva.
				if(isset($_REQUEST['qlikiframe_modid'])){
					$result[]='ERROR';
					$result[]=getTranslatedString('NO_ACTIVE_ANALYSIS','APP_STRINGS');
				}
			}
		}//aggiunto controllo su analisi singola disponibile
	}//end FOR  query result
	if($count_row==0 && isset($_REQUEST['qlikiframe_modid'])){
			//messaggio di errore: diverse cause.
			$result[]='ERROR';
			$result[]=getTranslatedString('MORE_CAUSES_NO_ANALYSIS','APP_STRINGS');
	}
	
	if(isset($_REQUEST['qlikiframe_modid']) && !$frame_perm){ //iframe non permesso, e sono in tab/widget
		//messaggio di errore che dice iframe non accessibile
		$result[]='ERROR';
		$result[]=getTranslatedString('IFRAMEID_NOT_PERMITTED','APP_STRINGS');
	}
	echo Zend_Json::encode($result);
}
// -- devo creare un array con error come primo elemento, e con la stringa da visualizzare come messaggio di errore.
else{
	if(isset($_REQUEST['qlikiframe_modid'])){ 
		$result[]='ERROR';
		if(!vtlib_isModuleActive('QlikIframe'))
			$result[]=getTranslatedString('QLIKIFRAME_NOACTIVE','APP_STRINGS');
		elseif(isPermitted('QlikIframe','')=="no")
			$result[]=getTranslatedString('QLIKIFRAME_NOPERMITTED','APP_STRINGS');
		else
			$result[]=getTranslatedString('GENERAL_ERROR_QLIK','APP_STRINGS');
		
		echo Zend_Json::encode($result);
	}
}

/******************** FUNCTIONS ***************************************/
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

//code to retrieve description from a id for field with uitype10
function retrieve_description_uitype10($modulename,$recordid){
	global $table_prefix, $adb;
	$query_entity=$adb->query("SELECT fieldname, tablename, entityidfield
												FROM ".$table_prefix."_entityname
												WHERE modulename='".$modulename."'");
	$entity_result=$adb->query_result($query_entity,0,'fieldname');
	
	//if there are some fields identifier, i put in array
	$array_fields= explode("," , $entity_result);
	
	//retrieve info about entityidfield and tablename
	$entityid=$adb->query_result($query_entity,0,'entityidfield');
	$table=$adb->query_result($query_entity,0,'tablename');
	
	//count lenght of array that contains the identifier/s field/s
	$count=count($array_fields);
	
	//retrieve value of field/s for current record
	$query_value= $adb->query("SELECT ".$entity_result." FROM ".$table. " WHERE ".$entityid."= ".$recordid);
	
	//if there are more than 1 indentifiers fields, concatenate values to create the final value
	if ($count>1){//scorro l'array di prima, e recupero tutti i valori
		foreach ($array_fields as $fieldname){
			if(empty($vte_field_final)){
				$vte_field_final = $adb->query_result($query_value,0,$fieldname);
			}
			else{
				$vte_field_final .= ' '.$adb->query_result($query_value,0,$fieldname);
			}
		}
	}
	else{// ONLY 1 identifier field, retrieve value and put directly in the variable used than
		$vte_field_final=$adb->query_result($query_value,0,$entity_result);
	}
	return $vte_field_final;
}

?>
