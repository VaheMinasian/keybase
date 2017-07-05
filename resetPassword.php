 <?php
 $error="";
 $success ="";

 if(isset($_GET['action']))
 {          
 	if($_GET['action']=="reset")
 	{
 		include('connection.php');

 		$encrypt = mysqli_real_escape_string($link, $_GET['encrypt']);
 		$query = "SELECT * FROM `usertable` WHERE md5(1290*3+id)='".$encrypt."' LIMIT 1";
 		$result = mysqli_query($link, $query);

 		if(mysqli_num_rows($result)==1){
 			$row = mysqli_fetch_array($result);

 			if(isset($_POST['reset-my-password'])){
 				if($_POST['email']!=$row['email']){
 					 $error = 'wrong email, try again.';
 				}else if ($_POST['password']!= $_POST['confirm-password']){
 					$error = 'Your passwords do not match, please try again.';
 				}

 				$uppercase = mysqli_real_escape_string($link, preg_match('@[A-Z]@', $_POST['password']));
 				$lowercase = mysqli_real_escape_string($link, preg_match('@[a-z]@', $_POST['password']));
 				$number    = mysqli_real_escape_string($link, preg_match('@[0-9]@', $_POST['password']));

 				if (($_POST['password'] == $_POST['confirm-password']) && (!$uppercase || !$lowercase || !$number || strlen($_POST['password']) < 8)){

 					$error .= "Password must be at least 8 characters, with minimum of 1 upperase, lowercase and number";
 				} else{

 					$email      = mysqli_real_escape_string($link,$_POST['email']);
 					$password     = mysqli_real_escape_string($link,$_POST['password']);
 					$query = "SELECT * FROM `usertable` WHERE md5(1290*3+id)='".$encrypt."' LIMIT 1";
 					$result = mysqli_query($link,$query);

 					if(mysqli_num_rows($result)==1){
 						$row = mysqli_fetch_array($result);
 						$query = "UPDATE `usertable` SET password ='".md5(md5($row['id']).$_POST['password'])."' WHERE id='".$row['id']."' LIMIT 1";
 						mysqli_query($link, $query);

 						$success = "Your password changed sucessfully <a href=\"http://matchanyckeln-com.stackstaging.com/index.php\">click here to login</a>.";

 						$to=$_POST['email'];
 						$subject="Your Keybase password has been changed";
 						$from = 'Keybase-no-reply';
 						$body='Hi '.$row['name'].',<br><br> Your Keybase password has been successfully changed.<br><br>With kind regards,<br>The Keybase System'. "\r\n";

 						$headers = "From: " . strip_tags($from) . "\r\n";
 						$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
 						$headers .= "MIME-Version: 1.0\r\n";
 						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

 						mail($to,$subject,$body,$headers);

 					}
 					else
 					{
 						$error = 'Invalid key please try again. <a href="http://matchanyckeln-com.stackstaging.com/index.php">KeyBase</a>';
 					}
 				}
 			}

 		}
 		else
 		{
 			$error = 'Invalid key please try again later. <a href="http://matchanyckeln-com.stackstaging.com/index.php">KeyBase</a>';
 		}
 	}
 }
 else
 {
 	header("location: /index.php");
 }

 ?>


 <?php include("header.php"); ?>

 <section class="container form-group">
 	<header class="centerAlign" id="the-title">
 		<h2>Reset Password</h2>
 	</header>
 	<div id="message">
 		<?php if($error!="") { 
 			echo '<div class="alert alert-danger alert-dismissible text-center fade show" role="alert"><button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$error.'</div>';
 		} else if ($success!=""){
 			echo '<div class="alert alert-success alert-dismissible text-center fade show" role="alert" ><button  type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$success.'</div>';
 		} 
 		?>
 	</div>

	<form method="post" class="form-horizontal col-sm-12" id="Login-form">
 		<div class="form-group row">
 			<label for="email" class="col-sm-5 col-form-label">Your Email:</label>
 			<div class="col-sm-7">
 				<input class="form-control reset-form" id="email" type="email" name="email" required="true" size="30" placeholder="someone@example.com"/>
 			</div>
 		</div>

 		<div class="form-group row rows">
 			<label for="password" class="col-sm-5 col-form-label">Your Password:</label>
 			<div class="col-sm-7">
 				<input id="password1" class="form-control reset-form" type="password" name="password" required="true" size="20" placeholder="your password"/>
 			</div>
 		</div>
 		<div class="form-group row rows">
 			<label for="password" class="col-sm-5 col-form-label">Confirm Password:</label>
 			<div class="col-sm-7">
 				<input id="password2" class="form-control reset-form" type="password" name="confirm-password" required="true" size="20" placeholder="your password"/>
 			</div>
 		</div>
 		<fieldset class="form-group row rows">
 			<input type="hidden" name="LogIn" value="1"/>
 			<input id="submitButton" class="btn btn-success reset-form" type="submit" name="reset-my-password" value="Reset Password"/>
 		</fieldset>
 		<div id="spin" class="spinner" >
 			<div class="bounce1"></div>
 			<div class="bounce2"></div>
 			<div class="bounce3"></div>
 		</div>
 	</form>
 </section>

 <style type="text/css">

 	.container{
 		max-width: 550px;
 		max-height: 520px;
 		margin: 0 auto;
 		margin-top: 50px !important;
 		margin-bottom: 20px !important;
 	}
 	.reset-form{
 		margin: 0 auto;
 		float: both;
 		text-align: center;
 	}
 	#submitButton{
 		margin-top: 20px;
 	}
 	#the-title{
 		margin-bottom: 30px;
 	}

 </style>

 <?php include("footer.php"); ?>