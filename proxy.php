<?php
// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

include ('webRequest.php');



$payload = (array) json_decode($_POST['data']);

$url = $_GET['url'];



$web = new webRequest;
$result = $web->getUrl($url,$url,$payload,'POST');
echo $result;
//
// echo 'x<pre>'.print_r($return,true).'</pre>';
// exit;
