<?php

require "../connect_db.php";

if(!$_POST['img']) die("There is no such product!");

$id=$_POST['img'];
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM internet_shop WHERE img='".$id."'"));

echo '{id:'.$row['id'].',nome:'.$row['name'].',txt:\'\
\
<table width="100%" id="table_'.$row['id'].'">\
  <tr>\
    <td width="60%">'.$row['name'].'</td>\
    <td width="10%">$'.$row['price'].'</td>\
    <td width="15%"><select name="'.$row['id'].'_cnt" id="'.$row['id'].'_cnt" onchange="change('.$row['id'].');">\
	<option value="1">1</option>\
	<option value="2">2</option>\
	<option value="3">3</option></slect>\
	\
	</td>\
	<td width="15%"><a href="#" onclick="remove('.$row['id'].');return false;" class="remove">remove</a></td>\
  </tr>\
</table>\'}';
?>
