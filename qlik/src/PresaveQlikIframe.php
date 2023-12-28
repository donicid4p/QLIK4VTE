<?php 
$qlikiframe_fieldname='';
if($values['qlikiframe_module_related']=='-- Nessuno --' && $values['qlikiframe_page_type']!='Modulo'){
	$status = false;
	$message = getTranslatedString('Modulo','QlikIframe')." non puo` essere '-- Nessuno --' se ".getTranslatedString('Page Type','QlikIframe')." diverso 'Modulo'";
	$focus = 'qlikiframe_module_related';
}

?>