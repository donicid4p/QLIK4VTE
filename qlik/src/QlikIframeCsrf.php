<?php
// qlik_iframe

include_once('../../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');

$result = array();
$result['success'] = false;

if(isset($_POST['function_name'])){
	if($_POST['function_name'] == 'getCSRF') {
		$VP = VTEProperties::getInstance();
        	if ($VP->getProperty('security.csrf.enabled')) {
           		$VTECSRF = new VteCsrf();
            		$result['data']['csrf'] = $VTECSRF->csrf_get_tokens();
        	} else {
            		$result['data']['csrf'] = '';
        	}
	$result['success'] = true;
	}
}

die(json_encode($result));


?>
