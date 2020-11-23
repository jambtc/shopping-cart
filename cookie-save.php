<?php

define('INCLUDE_CHECK',1);
require "connect.php";


if (isset($_POST['Api'])){
	setcookie("X-PUBLIC-KEY", $_POST['Api']['key_public']);
	setcookie("X-PRIVATE-KEY", md5($_POST['Api']['key_public'].$_POST['Api']['key_secret']));
}


?>
<script>
window.location.href='index.php';
</script>
