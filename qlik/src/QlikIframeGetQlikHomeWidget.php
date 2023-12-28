<?php
//RECUPERA TUTTI I WIDGET (ID) DELLA HOME CON ANALISI QLIK ASSOCIATA.
//SERVE PER CONTROLLARE SE I WIDGET SONO IN QUESTO ARRAY O SE WIDGET STANDARD.
global $adb, $table_prefix, $current_user;
$result= array();
$q_iframe = $adb->pquery("SELECT * 




		FROM ".$table_prefix."_homestuff 
		WHERE stufftype = ?
		AND userid = ?
		AND visible =0",array('Qlik',$current_user->id));
$cont = $adb->num_rows($q_iframe);
if(isset($q_iframe) && $cont > 0){
	//imposto che ci sono risultati
	$result[]='Si';
	$ids= array();
	//scorro i risultati, mi recupero gli id degli stuff
	for($i=0;$i<$cont;$i++){
		$curr_id= $adb->query_result($q_iframe,$i,'stuffid');
		$ids[]=$curr_id;
	}
	$result[]=$ids;
}
else
	$result[]='No';

echo Zend_Json::encode($result);
?>