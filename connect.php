<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

/* Database config */
$db_host		= 'localhost';
$db_user		= 'root';
$db_pass		= 'napoli80126';
$db_database	= 'shoppingcart';
/* End config */


$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_database);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

?>
