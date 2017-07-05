<?php
session_start();

// <GET MODAL KEY>
if(isset($_POST['city']) AND isset($_POST['address'])) {

	include("connection.php");

	mysqli_set_charset( $link, 'utf8');
  $query = "SELECT * FROM `keytable` WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' AND city ='".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 5";    

  	$result= mysqli_query($link, $query);

 	if (false === $result) {
 		echo("connection failed");
    	die(mysqli_error($link));
	}

	$row = mysqli_fetch_array($result);
  	$tempkey = $row['keynumber'];
  	$tempcomments = $row['comments'];
  	$return ="";

  	$return.=$tempkey."||";
  	$return.=$tempcomments;

  	echo ($return);

mysqli_close($link);
}


 // <GET MODAL USER>
 if(isset($_POST['email'])) {

  include("connection.php");

  mysqli_set_charset( $link, 'utf8');
  $query = "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

   $result = mysqli_query($link, $query);

  if ($result === false) {
    echo("connection failed");
      die(mysqli_error($link));
  }

  $row = mysqli_fetch_array($result);
    $nameField = $row['name'];
    $levelField = $row['levelgroup'];
    $mobileField = $row['mobile'];
    $passwordField = $row['password'];

    $return1  ="";
    $return1 .=$nameField."||";
    $return1 .=$levelField."||";
    $return1 .=$mobileField."||";
    $return1 .=$passwordField."||";
    $return1 .=$passwordField;

    echo ($return1);

  mysqli_close($link);
 }

?>