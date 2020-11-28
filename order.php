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
						$total = 0;
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
						// per fare dei test ho predisposto degli stati già preconfezionati

						// commerciante: merchant id:
						// 2 sex_jam

						// utenti: BOLT customer id:
						// 2 sergio
						// 3 paolo

						// redirect_url is url where Rules Engine must send his response
						// test online https://dashboard.fidelize.tk/index.php?r=ipn/rules
						// test localhost http://localhost/fidelize-dashboard/index.php?r=ipn/rules

						// id_cart is 10 chars random id
						$chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
		        shuffle($chars);
		        $cart_id = implode(array_slice($chars, 0, 10));

						$return = array(
							'id'=>$cart_id,
							'redirect_url'=>'http://localhost/fidelize-dashboard/index.php?r=ipn/rules',
							'merchant_id'=>2,
							'customer_id'=>rand(2,3),
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
				<fieldset>
					<label>Merchant's plugin Payload</label>

					<div class="json-response" id='json-response'>
						<?php
							echo "<pre>".print_r( json_encode($return,JSON_PRETTY_PRINT),true)."</pre>";
						?>
					</div>
				</fieldset>
				<fieldset>
					<label>Payload sent to Rules Engine</label>
					<div class="json-response" id='json-rulesEnginePayload'>
						...
					</div>
				</fieldset>
				<fieldset>
					<label>Rules Engine response</label>
					<div class="json-response" id='json-rulesEngineResponse'>
						...
					</div>
				</fieldset>
				<?php
				// preparazione sign api
				$nonce = explode(' ', microtime());

				$request['data'] = print_r(json_encode($return),true);
				$request['nonce'] = $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');

				$postdata = http_build_query($request, '', '&');
				$path = 'http://localhost/fidelize-dashboard/index.php?r=ipn/sendToRulesEngine';
				$sign = hash_hmac('sha512', hash('sha256', $request['nonce'] . $postdata, true), base64_decode($_COOKIE['X-PRIVATE-KEY']), true);

				// echo '<pre>request: '.print_r($request,true).'</pre>';
				// echo '<pre>postdata: '.print_r($postdata,true).'</pre>';
				// echo '<pre>privatekey: '.print_r($_COOKIE['X-PRIVATE-KEY'],true).'</pre>';
				// echo '<pre>sign: '.print_r(base64_encode($sign),true).'</pre>';
				?>

				<input type='hidden' id="sendToBackendValues" value='<?php  echo print_r(json_encode($request),true); ?>' />


				<a href="#" class="button" id="sendToBackendButton">send to backend</a>
      </div>
    </div>

    <div class="bottom-container-border"></div>

  </div>
</div>
<script>
var sendToBackendButton = document.querySelector('#sendToBackendButton');
var backendUrl = '<?php echo $path; ?>';
function wait(ms) { const start = performance.now(); while(performance.now() - start < ms); }

sendToBackendButton.addEventListener('click', function(){
	function repeated_ajax_check() {
		$.ajax({
			url: backendUrl,
			type: "POST",
			data:{
				'data'	: $('#sendToBackendValues').val(),
			},
			dataType: "json",
			beforeSend: function(xhr) {
				$('#json-rulesEngineResponse').text('');
	      xhr.setRequestHeader('API-Key', '<?php echo $_COOKIE['X-PUBLIC-KEY']; ?>');
				xhr.setRequestHeader('API-Sign', '<?php echo base64_encode($sign); ?>');
	    },
			success:function(data){
				console.log('response:',data);
				if (data.success==1){
					$('#json-rulesEngineResponse').text(data.message);
					$('#json-rulesEnginePayload').text(JSON.stringify(data.payload,' ',2));
				}

				if (data.error){
					$('#json-rulesEngineResponse').text(data.error);
					// console.log("Response error. Trying again...");
          // wait(5000);
          // repeated_ajax_check();
				}
			},
			error: function(j){
				console.log(j);
				console.log("Ajax error. Trying again...");
				wait(10000);
				repeated_ajax_check();
			}
		});
	}
	repeated_ajax_check()


});

</script>

</body>
</html>
