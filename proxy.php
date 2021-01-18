<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require ('proxyCurl.php');

// echo '<pre>'.print_r($_POST,true).'</pre>';
// exit;

$payload = (array) json_decode($_POST['data']);
$url = $_GET['url'];

$web = new proxyCurl;

// if ($web->isLocalhost()){
//   require ('proxyPassword.php');
//   $web->setProxy($proxy);
// }
$result = $web->getUrl($url,$payload);
echo $result;
