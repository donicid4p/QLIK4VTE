<?php 
global $adb, $qlik_proxy_url, $table_prefix, $current_user;
$result = array();
if(!empty($_REQUEST['stuffid']) && vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){ // aggiunta controllo permessi al modulo QlikIframe
	$stuffid = $_REQUEST['stuffid'];
	$q_iframe = $adb->pquery("SELECT * FROM qlikiframe_homeqlikanalysis WHERE homeqlikid = ?",array($stuffid));
	$iframeid = $adb->query_result($q_iframe,0,'qlikiframeid');
	$q_info_qlik =$adb->pquery("SELECT *, ifr.qlikiframeid AS qlikiframe_id_iframe, (SELECT debug FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1 ) AS debug
			FROM ".$table_prefix."_qlikiframe AS ifr
			INNER JOIN ".$table_prefix."_qlikiframecf AS cf ON ifr.qlikiframeid=cf.qlikiframeid
			INNER JOIN ".$table_prefix."_crmentity ON crmid=ifr.qlikiframeid AND deleted =0
			WHERE ifr.qlikiframeid = ?", array($iframeid));

	if(isset($q_info_qlik) && $adb->num_rows($q_info_qlik)> 0){
		//--------------------------------------------------------------------------------------//
		//									ATTENZIONE!!!
		//
		// QUALORA VENISSE MODIFICATO IL PROCESSO SOTTOSTANTE OCCORRE MODIFICARE ANCHE I FILE:
		// - QlikIframeAjax.php
		// - QlikIframeAjax2.php
		// - QlikIframeLoadCurrentTabAjax.php
		// - QlikIframeModAjax.php
		// - QlikIframePreviewAjax.php
		//--------------------------------------------------------------------------------------//
		
		//controllo se singolo iframe accessibile per l'utente
		$current_id_iframe = $adb->query_result($q_info_qlik,$i,'qlikiframe_id_iframe');
		if(isPermitted("QlikIframe","index",$current_id_iframe)!= "no"){
		
			//recupero le informazioni 
			//controllo se attiva l'analisi scelta
			$active= $adb->query_result($q_info_qlik,0,'qlikiframe_active');
	
			if($active <> 'No'){
				//se attivo, procedo con il recupero delle info che mi servono
				//recupero il codice o parte di esso, fatto per esempio in QlikIframeAjax2.php non gestendo i filtri riguardanti il dettaglio del record ma solo quelli con valori secchi.
				$newurl="";
				
				$riporta=true;//used to control if there are credentials for qlik sense
				$type= $adb->query_result($q_info_qlik,$i,'qlikiframe_type');
		
				//******************************** 	QLIKSENSE *********************************/
				//case when the type of analysis is qlik sense. Must concatenate link with other informations.
					
				$sense_url=$adb->query_result($q_info_qlik,$i,'qlikiframe_link');
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
		
					$pwd= $adb->query_result($other_info,0, 'qlikiframe_pwd_qlik');
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
					for ($j=0; $j<7;$j++){
						$k= $j+1;
						$check_values=recover_check_value($q_info_qlik,$i,$k);
						$val="1";
						if($check_values==$val){//the filter is set, recover che sense field and value
							$field= recover_qlik_field($q_info_qlik,$i,$k);
							$value= recover_vte_value($q_info_qlik,$i,$k);
								
							//control if che textbox are filled. In this case, control che field value, if is a sequence of values or a vte field
							if(!empty($field)&& !empty($value)){
								//IS A VTE FIELD
								$value=trim($value);
								$field_value=substr($value,0,3) == '|$|' && substr($value,-3) == '|$|'; //if value is a field and want take id (if field is uitype 10) or field value
								$field_descr=substr($value,0,4) == '|$d|' && substr($value,-4) == '|$d|'; //if value is a field and want take the identifier field for id (if field is uitype 10)
								$field_entity=substr($value,0,4) == '|$r|' && substr($value,-4) == '|$r|';
								if($field_value||$field_descr||$field_entity) {} //non faccio nulla, in quanto non sono in dettaglio di un record
		
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
						// modificato da mauver per gestire una unica utenza di qlik 
							// prima della modifica -> $newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url);
						$newurl=$qlik_proxy_url.$username.$pwd."&url=".base64_encode($sense_url."&identity=2".$current_user->id);
					}
					//die("EIFrameLoadHomeWidgetAjax ".$newurl);
				}
				//******************************** END	QLIKSENSE *********************************/
				//if the iframe must show (there are credentials, add a element in the array,
				if($riporta){
					$result[]='CORRECT';
					$result[]=array($adb->query_result($q_info_qlik,$i,'qlikiframe_name'),$newurl, $adb->query_result($q_info_qlik,$i,'qlikiframe_height'));
					$result[] = $adb->query_result($q_info_qlik,$i,'debug');
				}
				else{
					$result[]='ERROR';
					$result[]=getTranslatedString('CREDENTIALS_WRONG','APP_STRINGS');
					//messaggio di errore: credenziali non valide per l'analisi selezionata. Controllare le impostazioni utente
				}		
			}//end active != no
			else{
				//messaggio di errore: analisi non attiva.
				$result[]='ERROR';
				$result[]=getTranslatedString('NO_ACTIVE_ANALYSIS','APP_STRINGS');
			}
		} //end iframeid permitter for current user
		else{
			//messaggio di errore: iframe non accessibile
			$result[]='ERROR';
			$result[]=getTranslatedString('IFRAMEID_NOT_PERMITTED','APP_STRINGS');
		}
	}//end if exists analysis 
	else{
		//messaggio di errore: ananlisi cancellata.
		$result[]='ERROR';
		$result[]=getTranslatedString('DELETED_ANALYSIS','APP_STRINGS');
	}
}//end module active and permitted.
else{
	$result[]='ERROR';
	if(!vtlib_isModuleActive('QlikIframe'))
		$result[]=getTranslatedString('QLIKIFRAME_NOACTIVE','APP_STRINGS');
	elseif(isPermitted('QlikIframe','')=="no")
		$result[]=getTranslatedString('QLIKIFRAME_NOPERMITTED','APP_STRINGS');
	else
		$result[]=getTranslatedString('GENERAL_ERROR_QLIK','APP_STRINGS');
}

echo Zend_Json::encode($result);

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