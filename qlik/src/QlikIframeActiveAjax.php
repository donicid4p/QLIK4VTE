<?php
//1: controllo se il modulo qlikiframe attivo e permesso. In caso ritona no. 
//2: Se si, controlla quanti iframe ci sono attivi per tipo modulo per la creazione della voce dinamica a menu
//3: recupero tutte le analisi attive, di qualsiasi tipologia, per dare l'opzione di inserirle in un widget in home
global $adb, $qlik_proxy_url, $table_prefix, $current_user;
$result=array();
//control that the module Iframe is active and permitted for the current user
	if(vtlib_isModuleActive('QlikIframe') && isPermitted('QlikIframe','')!= "no"){ //modificata seconda condizione
		$result[]= 'Si'; //index 0
	}
	else{
		$result[]='No'; //index 0
	}
	$num_iframe=0; // utile per accorpare alcune info poi 
	if($result[0] == 'Si'){
		//control if there is almost one iframe with type "Module" -- utilizzato per creare successivamente la voce di menu dinamica
		$statement="SELECT COUNT(*) AS quanti 
				FROM ".$table_prefix."_qlikiframe 
				INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
				INNER JOIN ".$table_prefix."_crmentity ON ".$table_prefix."_qlikiframe.qlikiframeid = crmid AND deleted = 0 
				WHERE qlikiframe_page_type ='Modulo' AND qlikiframe_active <> 'No'";
		$res = $adb->query($statement);
		$num_iframe= $adb->query_result($res,0,'quanti');
		if($num_iframe>0){
			$result[]='Si'; //index = 1
		}
		else{
			$result[]='No'; //index = 1
		}
		//IL PEZZO DI CODICE TRA TAG PICKLIST_WITH_IFRAME HA IL MEDESIMO CONCETTO 
		//DI QUELLO PRESENTE NEL FILE QlikIframeGetTabAnalysisAjax.php AD ECCEZIONE DI ALCUNE MODIFICHE
		//(es: qui recupero tutti gli iframe permessi mentre dall'altra parte solamentei 
		// quelli che rispettano anche altri parametri)
		//ENTRAMBI VENGONO UTILIZZATI PER CREARE LA PICKLIST CON L'ELENCO DEGLI IFRAME DISPONIBILI.
		//ATTENZIONE: 
		
		//PICKLIST_WITH_IFRAME START
		//recupero tutti i gli iframe disponibili, me ne frego della tipologia in quanto lavoro per widget in homepage.
		$q = $adb->pquery("SELECT * FROM ".$table_prefix."_qlikiframe 
				INNER JOIN ".$table_prefix."_qlikiframecf ON ". $table_prefix."_qlikiframe.qlikiframeid = ".$table_prefix."_qlikiframecf.qlikiframeid 
				INNER JOIN ".$table_prefix."_crmentity ON ".$table_prefix."_qlikiframe.qlikiframeid = crmid AND deleted = 0 
				WHERE qlikiframe_active <> 'No'",array());
		$n = $adb->num_rows($q);
		if(isset($q) && $n >0){
			$result[]='Si'; //index = 2
			$result[]='##START##'; //index = 3
			//ontrollo se per l'utente corrente risulta disponibile ogni iframe
			for($i=0; $i<$n; $i++){//da 3 escluso in poi, ho associazione id |##| nome
				$id= $adb->query_result($q,$i,'qlikiframeid');
				if(isPermitted('QlikIframe',"index",$id)!= "no"){ //parametro 2 impostato a index in quanto actionname = 3
					$name= $adb->query_result_no_html($q,$i, 'qlikiframe_name');
					$result[]=$id." |##| ".$name;
				}
			}
			$result[]='##END##';
			//controllo se per caso, non ho iframe disponibili a causa dei permessi controllando index di ##END##
			//se risulta essere 4, imposto l'elemento con index 2, a No.
			if ($result[4]=="##END##")
				$result[2]="No";
		}
		else{
			$result[]='No'; //index = 2
			$result[]='##START##'; //index = 3
			$result[]='##END##'; //index = 4
		}
		//PICKLIST_WITH_IFRAME END
	} else {
		$result[]='No'; //index 1
		$result[]='No'; //index 2

		$result[]='##START##'; //index = 3
		$result[]='##END##'; //index = 4
	}
	echo Zend_Json::encode($result);

?>