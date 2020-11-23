<?php

define('INCLUDE_CHECK',1);
require "connect.php";



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Send! | Sergio Casizzone's shopping cart</title>
	<link rel="stylesheet" type="text/css" href="shopping.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>

	<script type="text/javascript" src="simpletip/jquery.simpletip-1.3.1.pack.js"></script>
	<script type="text/javascript" src="script.js"></script>
	<script>
		$(document).ready(function(){
			$('a.button').css('display','block');
		});
	</script>
</head>


<body>
<div id="main-container">
  <div class="container">
  	<span class="top-label">
      <span class="label-txt">Send order</span>
    </span>
    <div class="content-area">
  		<div class="content">
			  <div class="clear"></div>
				<a href="index.php" class="button">return</a>

      </div>
    </div>

    <div class="bottom-container-border"></div>

  </div>
</div>

</body>
</html>
