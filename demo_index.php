<?php
/*
 // PHP Proxy example for Yahoo! Web services. 
// Responds to both HTTP GET and POST requests
 //
 // Author: Jason Levitt
 // December 7th, 2005
 //
 
// Allowed hostname (api.local and api.travel are also possible here)
 define ('HOSTNAME', 'http://www.avvocatoandreani.it/');
 
// Get the REST call path from the AJAX application
 // Is it a POST or a GET?
 //$path = ($_POST['yws_path']) ? $_POST['yws_path'] : $_GET['yws_path'];
 $url = HOSTNAME; //.$path;
 
// Open the Curl session
 $session = curl_init($url);
 
// If it's a POST, put the POST data in the body
 if ($_POST['yws_path']) {
   $postvars = '';
   while ($element = current($_POST)) {
     $postvars .= key($_POST).'='.$element.'&';
     next($_POST);
   }
   curl_setopt ($session, CURLOPT_POST, true);
   curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars);
 }
 
// Don't return HTTP headers. Do return the contents of the call
 curl_setopt($session, CURLOPT_HEADER, false);
 curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
 
// Make the call
 $xml = curl_exec($session);
 
// The web service returns XML. Set the Content-Type appropriately
 header("Content-Type: text/xml");
 
echo $xml;
 curl_close($session);*/
 
 
 
 ?>
 <!-- 
<fieldset>
	<iframe class="frm_teamlist" src="http://www.avvocatoandreani.it/servizi/interessi_rivalutazione.php" name="team_list" width="1024px" marginwidth="0" height="768px" align="top" allowtransparency="1" frameborder="0" scrolling="yes" hspace="0" vspace="0">
	</iframe>
</fieldset>
 -->
<iframe class="frm_teamlist" src="http://www.avvocatoandreani.it/servizi/interessi_rivalutazione.php" name="team_list" width="320px" marginwidth="0" height="200px" align="top" allowtransparency="1" frameborder="0" scrolling="yes" hspace="0" vspace="0">
	</iframe>
 <?php  


$url = "http://www.avvocatoandreani.it/servizi/interessi_rivalutazione.php"; // Sito da cui ricevere il codice  

$ch = curl_init($url);      
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
$res = curl_exec($ch); // $res contiene il codice HTML del sito  
curl_close($ch);  

echo "Il codice HTML di <strong>".$url."</strong> è: <hr />".htmlspecialchars($res);  

?> 
