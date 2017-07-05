<?php

	$link = mysqli_connect("yourserver", "username", "password", "yourID");

		if(mysqli_connect_error()){
			die ("Database Connection Error");
		}
?>