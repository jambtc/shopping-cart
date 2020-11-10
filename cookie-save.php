<?php

define('INCLUDE_CHECK',1);
require "connect.php";


if (isset($_POST['Api'])){
	setcookie("_XPUBLICKEY", $_POST['Api']['key_public']);
	setcookie("_XPRIVATEKEY", $_POST['Api']['key_secret']);
}


?>
<script>
window.location.href='index.php';
</script>
