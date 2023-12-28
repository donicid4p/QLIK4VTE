<?php
//FUNZIONE CHE RECUPERA TUTTI GLI IFRAME DISPONIBILI DA VISUALIZZARE IN TAB
//IN BASE ALLA PAGINA IN CUI DEVONO ESSERE VISUALIZZATI E AL MODULO PER IL QUALE
//SI VUOLE CREARE LA TAB. (in questo caso, lavora sia per tab in listiview sia in detailview
//GENERA I VALORI IDIFRAME - NOMEIFRAME CHE SERVONO PER POPOLARE LA PICKLIST
//DEGLI IFRAME DISPONIBILI.
//HA IL MEDESIMO CONCETTO CHE STA SOTTO ALLA PORZIONE DI CODICE IN QlikIframeActiveAjax.php 
//DELIMITATA DAL TAG PICKLIST_WITH_IFRAME MA CON ALCUNI DETTAGLI CHE LI DIFFERENZIANO 
//(es: qui filtro per alcuni parametri, di la invece recupero tutti gli iframe permessi)
global $adb, $table_prefix, $current_user;
$result=array();
if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')<>"no"){ // aggiunta controllo permessi al modulo QlikIframe
	//recupero tutti i gli iframe disponibili, per il modulo corrente e per il tipo di pagina in cui mi trovo
	$q = $adb->pquery("SELECT * 
		FROM ".$table_prefix."_qlikiframe 
		INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
		INNER JOIN ".$table_prefix."_crmentity ON ".$table_prefix."_qlikiframe.qlikiframeid = crmid AND deleted = 0 
		WHERE qlikiframe_active <> 'No' 
		AND qlikiframe_show_in_tab = 1 AND qlikiframe_page_type = ? 
		AND qlikiframe_module_related =? ",array($_REQUEST['qlikiframe_type'], $_REQUEST['qlikiframe_mod']));
	$n = $adb->num_rows($q);
	if(isset($q) && $n >0){
		$result[]='Si'; //index = 0
		$result[]='##START##'; //index = 1
		for($i=0; $i<$n; $i++){//da 1 escluso in poi, ho associazione id |##| nome
			$id= $adb->query_result($q,$i,'qlikiframeid');
			if(isPermitted('QlikIframe',"index",$id)!= "no"){ //parametro 2 impostato a index in quanto actionname = 3
				$name= $adb->query_result_no_html($q,$i,'qlikiframe_name');
				$result[]=$id." |##| ".$name;
			}
		}
		$result[]='##END##';//index almeno 2
		//controllo se per caso, non ho iframe disponibili a causa dei permessi controllando index di ##END##
		//se risulta essere 2, imposto l'elemento con index 0, a No.
		if ($result[2]=="##END##")
			$result[0]="No";
	}
	else{
			$result[]='No'; //index = 0
			$result[]='##START##'; //index = 1
			$result[]='##END##';//index = 2
	}
}
else{//modulo non attivo o non permesso.
	$result[]='Permission'; //index = 0
	$result[]='##START##'; //index = 1
	$result[]='##END##';//index = 2
}
echo Zend_Json::encode($result);

?>