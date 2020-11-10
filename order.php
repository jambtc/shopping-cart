<?php

define('INCLUDE_CHECK',1);
require "connect.php";

if(!$_POST)
{
	if($_SERVER['HTTP_REFERER'])
	header('Location : '.$_SERVER['HTTP_REFERER']);

	exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Checkout! | Sergio Casizzone's shopping cart</title>
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
      <span class="label-txt">Your order</span>
    </span>
    <div class="content-area">
  		<div class="content">
        <?php
					$cnt = array();
					$products = array();

					foreach($_POST as $key=>$value)
					{
						$key=(int)str_replace('_cnt','',$key);

						$products[]=$key;
						$cnt[$key]=$value;
					}

					$query = "SELECT * FROM internet_shop WHERE id IN(".join($products,',').")";

					if ($result = $mysqli->query($query)) {
						echo '<h1>You ordered:</h1>';

						while ($row = $result->fetch_assoc()) {
							echo '<h2>'.$cnt[$row['id']].' x '.$row['name'].'</h2>';
							$total+=$cnt[$row['id']]*$row['price'];

							$items[] = array(
								'product_id'=>$row['id'],
								'product_qty'=>$cnt[$row['id']],
								'product_price'=>$row['price'],
								'product_description'=>$row['name']
							);

						}
						// creazione array response json
						$return = array(
							'merchant_id'=>rand(1,10),
							'customer_id'=>rand(1000,100000),
							'order_number'=>rand(10000,99999),
							'order_total'=>$total,
							'items'=>$items
						);
						echo '<h1>Total: $'.$total.'</h1>';
					}else{
						echo '<h1>There was an error with your order!</h1>';
					}
				?>
			  <div class="clear"></div>
				<a href="index.php" class="button">return</a>
				</br>
				<h3>json response</h3>
				<div class="json-response">
					<?php
						echo "<pre>".print_r( json_encode($return,JSON_PRETTY_PRINT),true)."</pre>";
					?>
				</div>
				<a href="send.php" class="button">send to backend</a>
      </div>
    </div>

    <div class="bottom-container-border"></div>

  </div>
</div>

</body>
</html>
