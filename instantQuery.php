<?php
session_start();

if(isset($_POST['city']) AND isset($_POST['address'])) {

	include("connection.php");

	mysqli_set_charset( $link, 'utf8');
  $query = "SELECT * FROM `keytable` WHERE address like '".mysqli_real_escape_string($link, $_POST['address'])."%' AND city ='".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 3";    

  	$result= mysqli_query($link, $query);

 	if (false === $result) {
 		echo("connection failed");
    	die(mysqli_error($link));
	}

  if (mysqli_num_rows($result)==1){

	  $row = mysqli_fetch_array($result);
  	$tempkey = $row['keynumber'];
  	$tempcomments = $row['comments'];
  	$return ="";

  	$return.=$tempkey."||";
  	$return.=$tempcomments;

  	echo ($return);
 }

mysqli_close($link);
}

?>