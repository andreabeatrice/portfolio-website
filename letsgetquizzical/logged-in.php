<?php 
	require 'conn.php';

	$name = "";
	$surname = "";
	$date = "";

	function loggedIn($email, $pass, mysqli $con){
		$added = "SELECT * FROM tbaccounts WHERE email_address='".$email."' AND password='".$pass."'";
		$array = mysqli_query($con, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		$userInfo = array();

		foreach ($x as $val) {
			$userInfo[] = array(
	            "uId" => $val["user_id"],
	            "name" => $val["first_name"],
	            "surname" => $val["last_name"],
	            "email" => $val["email_address"],
	            "admin" => $val["admin"]
	         );

			$uId = $val["user_id"];
			$name = $val["first_name"];
			$surname = $val["last_name"];
			$email = $val["email_address"];
			$date = $val["date_of_birth"];
			$pass = $val["password"];
			$ad = $val["admin"];
			setcookie("user", $uId, time() + (86400 * 30), "/"); 
			

		}

		return $userInfo;
	}

	function li(mysqli $con){
		$added = "SELECT * FROM tbaccounts WHERE user_id='".$_COOKIE['user']."'";
		$array = mysqli_query($con, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		$userInfo = array();

		foreach ($x as $val) {
			$userInfo[] = array(
	            "uId" => $val["user_id"],
	            "name" => $val["first_name"],
	            "surname" => $val["last_name"],
	            "email" => $val["email_address"],
	            "password" => $val["password"],
	            "admin" => $val["admin"]
	         );
		}

		return $userInfo;

	}
?>