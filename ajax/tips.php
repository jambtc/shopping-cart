<?php

define('INCLUDE_CHECK',1);
require "../connect.php";

if(!$_POST['img']) die("There is no such product!");

$img = basename($_POST['img']);
$query = "SELECT * FROM internet_shop WHERE img='".$img."'";

if ($result = $mysqli->query($query)) {
  while ($row = $result->fetch_assoc()) {
    echo '<strong>'.$row['name'].'</strong>
          <p class="descr">'.$row['description'].'</p>
          <strong>price: $'.$row['price'].'</strong>
          <small>Drag it to your shopping cart to purchase it</small>';
  }
}
?>
