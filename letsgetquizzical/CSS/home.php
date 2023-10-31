<?php
	$name = "";
	$surname = "";
	$email = "";
	$date = "";
	$pass = "";

	if (isset($_POST['login'])){
		$mysqli = mysqli_connect("localhost", "root", "", "dbquizzical");

		$email = mysqli_real_escape_string($mysqli, $_POST['email']);
		$password = mysqli_real_escape_string($mysqli, $_POST['pass']);

		$added = "SELECT * FROM tbaccounts";
		$array = mysqli_query($mysqli, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			if(strcmp($email ,$val['email_address']) == 0){
				$rightpass = "SELECT * FROM tbaccounts WHERE email_address='$email'";

				$db_password = mysqli_query($mysqli, $rightpass);

				$j = mysqli_fetch_all($db_password, MYSQLI_ASSOC);

				foreach ($j as $v) {
					if(strcmp($password ,$v['password']) == 0){

						
						$name = $v["first_name"];
						$surname = $v["last_name"];
						$email = $v["email_address"];
						$date = $v["date_of_birth"];
						$pass = $v["password"];
						setcookie("LOGGED_IN", true, time() + (86400 * 10), "/"); 
						setcookie("EMAIL", $email, time() + (86400 * 10), "/"); 
						setcookie("PASS", $pass, time() + (86400 * 10), "/"); 
					}
					else {
						//invalid password- redirect to splash page
					}
				}
			}
			else {
				//error
			}

		}
	}
	else if (isset($_POST['register'])){
		$mysqli = mysqli_connect("localhost", "root", "", "dbquizzical");

		$name = $_POST["fname"];
		$surname = $_POST["lname"];
		$email = $_POST["email"];
		$date = $_POST["date"];
		$pass = $_POST["pass"];

		$query = "INSERT INTO tbaccounts (first_name, last_name, email_address, date_of_birth, password) VALUES ('$name', '$surname', '$email', '$date', '$pass');";

		$res = mysqli_query($mysqli, $query) == TRUE;

		if($res){
			setcookie("LOGGED_IN", true, time() + (86400 * 10), "/"); 
			setcookie("EMAIL", $email, time() + (86400 * 10), "/"); 
			setcookie("PASS", $pass, time() + (86400 * 10), "/"); 
		}
	}
	else {

	}

	if (isset($_COOKIE['LOGGED_IN'])){
		//echo "<h1>WORKING</h1>";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>let's get quizzical</title>
	<meta name="author" content="Andrea Blignaut">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<link rel="icon" href="media/icon.ico" type="image/icon type">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
</head>
<body id="home_body">
	<div id="head">
		<?php include 'header.php'; ?>
	</div>


</body>
</html>