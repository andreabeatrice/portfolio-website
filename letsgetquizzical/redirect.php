<?php 
	$u = $_SERVER["SCRIPT_FILENAME"];

	if (!isset($_COOKIE['user'])) {
		header("Location: index.php");
		die();
	}

	if(strpos($u, 'admin') !== false){
		include('logged-in.php');
	
		$userInfo = li($mysqli)[0];

		if ($userInfo["admin"] != 1){
			header("Location: index.php");
			die();
		}

	}

?>