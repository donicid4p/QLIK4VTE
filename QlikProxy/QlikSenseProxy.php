<?php

include_once(__DIR__."/../config.inc.php");
chdir($root_directory);
require_once('include/utils/utils.php');
require_once('vtlib/Vtecrm/Module.php');

chdir($root_directory);




global $adb, $table_prefix;

$conf = $adb->query("SELECT * FROM {$table_prefix}_qlikiframeconfs WHERE active = 1 ORDER BY conf_id DESC LIMIT 1");
$conf = $adb->query_result_rowdata($conf, 0);

if (!$conf) {
echo "<strong>No active Qlik Sense Proxy configuration found</strong>";
exit;
}



//Path to call (with xrfkey parameter added)
$endpoint = $conf['endpoint'];

//Location of QRS client certificate and certificate key, assuming key is included separately

$QRSCertfile = __DIR__."/../".$conf['qrscertfile'];
$QRSCertkeyfile = __DIR__."/../".$conf['qrscertkeyfile'];


/*
$QRSCertfile = "/var/www/datasynapsi/QlikProxy/certificates/client.pem";
$QRSCertkeyfile = "/var/www/datasynapsi/QlikProxy/certificates/client_key.pem";
*/




$QRSCertkeyfilePassword = $conf['qrscertkeyfilepassword'];

/*
echo $QRSCertfile;
echo "<br>";
echo $QRSCertkeyfile;
echo "<br>";
*/

//Set up the required headers
$headers = array(
'Accept: application/json',
'Content-Type: application/json',
'x-qlik-xrfkey: 0123456789abcdef',
'X-Qlik-User: UserDirectory='.$_REQUEST['Password'].';UserId='.$_REQUEST['User']
);
//echo $conf['qrsurl'] . $endpoint . "<br>";
//Create Connection using Curl
$ch = curl_init($conf['qrsurl'] . '/qps/' .$endpoint.'/ticket?xrfkey=0123456789abcdef');

curl_setopt($ch, CURLOPT_VERBOSE, true);
// curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, '{
	"UserId":"'.$_REQUEST['User'].'",
	"UserDirectory":"'.$_REQUEST['Password'].'"
}');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSLCERT, $QRSCertfile);
curl_setopt($ch, CURLOPT_SSLKEY, $QRSCertkeyfile);
if ($QRSCertkeyfilePassword)
	curl_setopt($ch, CURLOPT_KEYPASSWD, $QRSCertkeyfilePassword);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//Execute and print response
$data = curl_exec($ch);

$jd = json_decode($data);
//die($jd->Ticket .' '.$jd->UserDirectory.' '.$jd->UserId.' '.base64_decode($_REQUEST['url']).'&qlikTicket='.$jd->Ticket); 
header('Location: '.base64_decode($_REQUEST['url']).'&qlikTicket='.$jd->Ticket);
?>
