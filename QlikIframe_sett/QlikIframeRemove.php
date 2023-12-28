<?php


require_once('modules/Settings/QlikIframe/core/QlikIframeInfo.php');

$scannerinfo = new QlikIframeInfo(trim($_REQUEST['confname']));

$scannerinfo->delete();

header('Location: index.php?module=Settings&action=QlikIframe&parenttab=Settings');

?>
