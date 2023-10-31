<?php 
	include('conn.php');

	$name = "";
	$surname = "";
	$date = "";
	//$mysqli = mysqli_connect("localhost", "root", "", "dbquizzical");
	$userId = isset($_POST['id']) ? $_POST['id'] : null;


	$result = array();
	$datae = array();

 	$q = "SELECT * FROM tbaccounts WHERE user_id='".$userId."' ";
	$r = $mysqli->query($q);
	while ($row = $r->fetch_assoc()) {
		$result = $row;
	}


	echo json_encode($result); 

?>