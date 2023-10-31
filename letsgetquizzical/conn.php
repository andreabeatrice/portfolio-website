<?php
	$mysqli = mysqli_connect('localhost:3306', 'andreab1_login', 'I1wq9cplNx?F98olu', 'letsgetquizzical'); //connect to a database 

	if (!$mysqli){
		echo '<script> console.log("Connection error: '. mysqli_connect_error().'");</script>';
	}
	else {
		//echo '<script> console.log("connected to localhost");</script>';
	}
?>