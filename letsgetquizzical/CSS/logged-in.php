<?php 
	$name = "";
	$surname = "";
	$date = "";
	$mysqli = mysqli_connect("localhost", "root", "", "dbquizzical");

	if (isset($_COOKIE['LOGGED_IN'])) {
		$added = "SELECT * FROM tbaccounts WHERE email_address='".$_COOKIE['EMAIL']."' AND password='".$_COOKIE['PASS']."'";
		$array = mysqli_query($mysqli, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			$name = $val["first_name"];
			$surname = $val["last_name"];
			$email = $val["email_address"];
			$date = $val["date_of_birth"];
			$pass = $val["password"];
		}
	}

?>