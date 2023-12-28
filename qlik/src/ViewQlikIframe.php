<?php 
global $sdk_mode, $table_prefix, $current_user;

/*
Aggiunto il campo qlikiframe_txt_mod_name per utilizzarlo esclusivamente in detailview del record in quanto
la picklist dei moduli nasce con il solo valore '-- Nessuno --' salvato a databse
e viene popolata dinamicamente.
In questo modo il campo qlikiframe_txt_mod_name (campo testuale) contiene il valore del campo qlikiframe_module_related
e viene modificato ogni volta che viene salvato il record.

In caso di creazione/modifica il campo qlikiframe_txt_mod_name viene nascosto e viene mostrato il campo
qlikiframe_module_related solamente in queste 2 occasioni (quindi non in detailview) 
*/

switch($sdk_mode) {
	case 'detail':
		if($fieldname=='qlikiframe_module_related') {
			$readonly = 100;
			$success = true;
		}
		if($fieldname=='qlikiframe_txt_mod_name') {
			$col_fields['qlikiframe_txt_mod_name']= getTranslatedString($col_fields['qlikiframe_txt_mod_name'],$col_fields['qlikiframe_txt_mod_name']);
			$readonly = 99;
			$success = true;
		}
		break;
	case '':
	case 'edit':
		if($fieldname=='qlikiframe_txt_mod_name') {
			$readonly = 100;
			$success = true;
		}
		break;
}
?>