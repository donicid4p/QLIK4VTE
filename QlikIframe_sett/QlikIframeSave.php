<?php




require_once('modules/Settings/QlikIframe/core/QlikIframeInfo.php');

global $app_strings, $mod_strings, $currentModule, $theme, $current_language, $site_URL;

$confname = vtlib_purify(trim($_REQUEST['confname']));

$conf_id = vtlib_purify(trim($_REQUEST['conf_id']));

$endpoint     = vtlib_purify(trim($_REQUEST['endpoint']));

$qrsurl = vtlib_purify(trim($_REQUEST['QRSurl']));

$active  = vtlib_purify(trim($_REQUEST['active']));
$active = $active == 'true' ? 1: 0;


$qrscertfile = vtlib_purify(trim($_REQUEST['QRSCertfile']));

$qrscertkeyfile = vtlib_purify(trim($_REQUEST['QRSCertkeyfile']));

$qrscertkeyfilepassword = vtlib_purify(trim($_REQUEST['QRSCertkeyfilePassword']));
$debug = vtlib_purify(trim($_REQUEST['debug']));
$debug = $debug == 'true' ? 1: 0;

$newconfinfo = new QlikIframeInfo(false, false);
$newconfinfo->conf_id = $conf_id;
$newconfinfo->confname = $confname;
$newconfinfo->endpoint = $endpoint;
$newconfinfo->active = $active;
$newconfinfo->QRSCertfile = $qrscertfile;
$newconfinfo->QRSCertkeyfile = $qrscertkeyfile;
$newconfinfo->QRSCertkeyfilePassword = $qrscertkeyfilepassword;
$newconfinfo->QRSurl = $qrsurl;
$newconfinfo->debug = $debug;



$confinfo = new QlikIframeInfo(trim($_REQUEST['hidden_confname']));

$confid = $confinfo->update($newconfinfo);


//require('modules/Settings/QlikIframe/QlikIframeInfo.php');
//header('Location: index.php?module=Settings&action=QlikIframe&parenttab=Settings&reset_session_menu=true');
echo '<meta http-equiv="refresh" content="0;url='.$site_URL.'/index.php?module=Settings&action=QlikIframe&parenttab=Settings&reset_session_menu=true">';


?>
