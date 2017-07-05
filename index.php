<?php
session_start();
$error = "";
$success ="";

if(isset($_COOKIE['id'])) {
	setcookie(session_name(), '', time() - 60*60*24*365);
}

if(array_key_exists("logout", $_GET)){
	session_unset();
	setcookie("id", "", time() -60*60);
	unset($_COOKIE['id']);

} else if((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
	header("Location: loggedInPage.php");
}

if(array_key_exists("submit", $_POST)){
	
	include("connection.php");

	
	if(!$_POST['email']){
		$error .= "An email adress is required<br>";
	}
	if(!$_POST['password']){
		$error .= "A password is required<br>";
	}
	if ($error != ""){
		$error = "<p>There were error(s) in your form:</p>".$error;
	} else{
		mysqli_set_charset( $link, 'utf8');
		$query= "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'"; 
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		if(isset($row)) {
			$hashedPassword = md5(md5($row['id']).$_POST['password']);
			if ($hashedPassword == $row['password']){
				$_SESSION['id'] = $row['id'];

				if(isset ($_POST['stayLoggedIn'])){
					if ($_POST['stayLoggedIn'] == '1') {

						setcookie("id", $row['id'], time() + 60*60*24*30);
					}}
					header("Location: loggedInPage.php");
				} else {
					$error = "invalid email or password, please try again";
				}
			} else{
				$error = "that email/password combination could not be found";
			}
		}
	}

	if(array_key_exists("sendReminder", $_POST)){

		include("connection.php");

		if(!$_POST['email']){
			$error .= "An email adress is required<br>";
		} else{
			$query= "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'"; 
			$result = mysqli_query($link, $query);
				$row = mysqli_fetch_array($result);
			if (mysqli_num_rows($result)==0){
				$error .= "no such user exists in database.";           
			} else if ((mysqli_num_rows($result)==1)&& ($row['state']==="passive"))
				$error .= "Your account is not activated yet<br>";
			else{
				if(isset($row)) {
					$encrypt = md5(1290*3+$row['id']);
					$message = "your password reset link sent to your email address.";
					$to=$_POST['email'];
					$subject="Your password reset link";
					$from = 'Keybase-no-reply';
					$body='Hi '.$row['name'].',<br><br> We received a password change request from your email address. If it wasn\'t you who requested this change, then simply ignore this email.<br> We apologize for inconvenience. <br><br><strong> If it was you who requested a password reset then please click the following link to reset your password</strong> http://key-com.stackstaging.com/resetPassword.php?encrypt='.$encrypt.'&action=reset   <br><br>'."\r\n";

					$headers = "From: " . strip_tags($from) . "\r\n";
					$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF8\r\n";

					if(mail($to,$subject,$body,$headers)){
						$success .= "password reset link sent successfully!<br>check your email.<br>";

					}
				}else{
					$error .= "that email/password combination could not be found";
					die(mysqli_error($link));
				}
			}
		}
	}
?>

	<?php include("header.php"); ?>

	<section class="container form-group">
		<header class="centerAlign" id="the-title">
			<h1 id="page-title">KEYBASE</h1>
			<p id="explanatory-text">Log in to the key database</p>
		</header> 

		<div id="message">
			<?php if($error!="") {
				echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'.$error.'</div>';
			}
			else if ($success!="") {
				echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'.$success.'</div>';
			} 
			?>
		</div>

		<!--LOG IN FORM -->

		<form method="post" id="Login-form">
			<div class="form-group row">

				<label for="email" class="col-sm-3 col-form-label">Email:</label>
				<div class="col-sm-9">
					<input class="form-control" id="email" type="email" name="email" required="true" placeholder="someone@example.com"/>
				</div>
			</div>

			<div class="form-group row">
				<label for="password" class="col-sm-3 col-form-label">Password:</label>
				<div class="col-sm-9">
					<input class="form-control" id="password" type="password" name="password" placeholder="your password" required="true"/>
				</div>
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="stayLoggedIn" value="1"/> Stay logged in
				</label>
			</div>
			<fieldset class="form-group">
				<input type="hidden" name="LogIn" value="1"/>
				<input class="btn btn-success" type="submit" name="submit" id="log-in" value="Log in"/>
				<div id="spin" class="spinner">
					<div class="bounce1"></div>
					<div class="bounce2"></div>
					<div class="bounce3"></div>
				</div>
			</fieldset>
			<div id="remind-password-link">
				<a href="#" type="text" role="button">Forgot password?</a>		
			</div>
			<div id="password-reminder">
				<button type="submit" name="sendReminder" id="sendReminder" class="btn">send reset link</button>
			</div>
		</form>
	</section>

	<?php include("footer.php"); ?>