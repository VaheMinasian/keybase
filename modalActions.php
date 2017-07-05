<?php

function randomCode() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ00122344567789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
    	$n = rand(0, $alphaLength);
    	$pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}				

if(array_key_exists("addEntry", $_POST)){
	if(!$_POST['address']){
		$dangerAlert .= "Address field is required";
	} else {
		$query = "SELECT * FROM `keytable` WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' AND city = '".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 1";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result)>0){
			$infoAlert .= "Address: " .$_POST['address'].", ".$_POST['city']."<br> already exists, no changes made.";
		} else {    
			$query = "INSERT INTO `keytable` (`address`,`city`, `keynumber`, `comments`) VALUES ('".mysqli_real_escape_string($link, $_POST['address'])."', '".mysqli_real_escape_string($link, $_POST['city'])."', '".mysqli_real_escape_string($link, $_POST['keynumber'])."', '".mysqli_real_escape_string($link, $_POST['comments'])."' )";

			if(mysqli_query($link, $query)){
				$successAlert .= "entry: ". $_POST['address'].", ".$_POST['city']. " added successfully.";   
			} else{
				$dangerAlert .= "failed adding entry:<br> ". $_POST['address'].", ".$_POST['city'] .".";           
			}
		}   
	} 
} else if(array_key_exists("updateEntry", $_POST)){

	if(!$_POST['address']){
		$dangerAlert .= "Address field is required";
	} else {
		$query = "SELECT * FROM `keytable` WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' AND city = '".mysqli_real_escape_string($link, $_POST['city'])."'LIMIT 1";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result)==0){
			$dangerAlert .= "Address: " .$_POST['address'].", ".$_POST['city']. "<br> doesn't exist.";           
		} else {    
			$query = "UPDATE `keytable` SET 
			keynumber = '".mysqli_real_escape_string($link, $_POST['keynumber'])."',
			comments = '".mysqli_real_escape_string($link, $_POST['comments'])."'
			WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' 
			AND city = '".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 1";

			if(mysqli_query($link, $query)){
				$successAlert .= "entry: ".$_POST['address'].", ".$_POST['city'] ."<br> updated successfully.";   
			} else{
				$dangerAlert .= "failed to update entry:<br> ".$_POST['address'].", ".$_POST['city'].".";           
			}
		}   
	}
} else if(array_key_exists("deleteEntry", $_POST)){

	if(!$_POST['address']){
		$dangerAlert .= "Address field is required";
	} else {
		$query = "SELECT * FROM `keytable` WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' AND city = '".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 1";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result)==0){
			$dangerAlert .= "Address: ".$_POST['address'].", ".$_POST['city']."<br> doesn't exist.";           
		} else {    
			$query = "DELETE FROM `keytable` WHERE address = '".mysqli_real_escape_string($link, $_POST['address'])."' AND city = '".mysqli_real_escape_string($link, $_POST['city'])."' LIMIT 1 ";

			if(mysqli_query($link, $query)){
				$successAlert .= "entry: ".$_POST['address'].", ".$_POST['city']."<br> deleted successfully.";   
			} else{
				$dangerAlert .= "failed to delete entry:<br> ".$_POST['address'].", ".$_POST['city'].".";           
			}
		}   
	}
	}// Users tab
	else if(array_key_exists("addUser", $_POST)){
		if((!$_POST['email']) && (!$_POST['name']) && (!$_POST['levelgroup'])){
			$dangerAlert .= "email, name and level fields are required";
		} else {
			$query = "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_array($result);
				$infoAlert .= "email: ". $_POST['email'] ."<br> already exists in database.";           
			} else {
				$password = randomCode(); 
				$random = randomCode();
				$passive = "passive";
				$query = "INSERT INTO `usertable` (`name`, `email`, `mobile`, `password`, `levelgroup`, `token`, `state`) VALUES ('".mysqli_real_escape_string($link, $_POST['name'])."', '".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['mobile'])."', '".mysqli_real_escape_string($link, $password)."', '".mysqli_real_escape_string($link, $_POST['levelgroup'])."', '".mysqli_real_escape_string($link, $random)."', '".mysqli_real_escape_string($link, $passive)."')";

				if(!mysqli_query($link, $query)){
					$dangerAlert .= "failed adding user: ".$_POST['name'].", ".$_POST['email'].".";           

				}
				$successAlert .= "user: ".$_POST['name'].", ".$_POST['email']." added.<br>";


				$id = mysqli_insert_id($link);
				$token = md5(1290*3+$id);
				$message = "Welcome to Keybase, please click on the link to set your password reset link sent to your email address.";
				$to= $_POST['email'];
				$subject = "Welcome to keybase";
				$from = 'Keybase-no-reply';
				$body='Hi '.$_POST['name'].',<br><br> We give you a warm welcome on joining our team.<br> use your email as login to our website.<br><br> Please click the following link to reset your password</strong> http://key-com.stackstaging.com/activateAccount.php?encrypt='.$token.'&action=reset   <br><br>'."\r\n";

				$headers = "From: " . strip_tags($from) . "\r\n";
				$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

				if(mail($to,$subject,$body,$headers)){
					$successAlert .= " Password setting link sent successfully.";
				}
				else { 
					$dangerAlert .= "couldn't send an email to the user:<br> ".$_POST['name'].", ".$_POST['email'];
				}
			}	
		}
	} else if(array_key_exists("updateUser", $_POST)){
		if(!$_POST['email']){
			$dangerAlert .= "email field is required";
		} else {
			$query = "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result)==0){
				$dangerAlert .= "nuser: ".$_POST['name'].", ".$_POST['email']." exists in database.";           
			} else {    
				$row = mysqli_fetch_array($result);

				$check= false;
				$query = "UPDATE `usertable` SET ";
				$rowArray = array('name', 'mobile', 'levelgroup');
				foreach ($rowArray as $field) {
					if (isset($_POST[$field]) and !empty($_POST[$field])) {

						$var = mysqli_real_escape_string($link, $_POST[$field]);
						$query .= $field . " = '$var',";
						$check = true;
					}
				}
				if ($check){
					$query = substr($query,0,-1);
					$query .= " WHERE id = '".mysqli_real_escape_string($link, $row['id'])."' LIMIT 1";
					if(mysqli_query($link, $query)){
						$successAlert .= "user: ".$_POST['name'].", ".$_POST['email']." updated successfully.";   
					}
					else
						$dangerAlert .= "failed to update user data: ".$_POST['name'].", ".$_POST['email'].".";           
				} else{
					$infoAlert .= "no changes made to user: ".$_POST['name'].", ".$_POST['email'].".";
				}
			}
		}
	}	
	else if(array_key_exists("deleteUser", $_POST)){

		if((!$_POST['email']) || (!$_POST['name'])){
			$dangerAlert .= "name and email fields are required";
		} else {
			$query = "SELECT * FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link, $query);
			if (mysqli_num_rows($result)==0){
				$dangerAlert .= "user: ".$_POST['name'].", ".$_POST['email']." doesn't exist.";
			} else {  
				$row = mysqli_fetch_array($result);
				if(!($row['name']==$_POST['name'])){
					$dangerAlert .= "wrong name, try again."; 
				} else{
					$query = "DELETE FROM `usertable` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1 ";
					if(mysqli_query($link, $query)){
						$successAlert .= "user: ".$_POST['name'].", ".$_POST['email']." deleted successfully.";   
					} else{
						$dangerAlert .= "failed to delete user: ".$_POST['name'].", ".$_POST['email'].".";           
					}
				}
			}   
		}
	}
	?>