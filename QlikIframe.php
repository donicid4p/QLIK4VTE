<?php


require_once('include/utils/utils.php');

$mode = $_REQUEST['mode'];

if($mode == 'Ajax' && !empty($_REQUEST['xmode'])) {
	$mode = $_REQUEST['xmode'];
}




 if($mode == 'edit') {
	include('modules/Settings/QlikIframe/QlikIframeEdit.php');
} else if($mode == 'save') {
	include('modules/Settings/QlikIframe/QlikIframeSave.php');
} else if($mode == 'remove') {
	include('modules/Settings/QlikIframe/QlikIframeRemove.php');
} else {
	include('modules/Settings/QlikIframe/QlikIframeInfo.php');
}
?>