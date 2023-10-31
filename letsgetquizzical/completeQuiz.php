<?php
	include('logged-in.php');
	include 'conn.php';

	$userScore = $_POST['score']; 
	$name = $_POST['quiz_name'];
	$from = $_POST['from']; 

	$added = "SELECT quiz_id FROM tbquizzes WHERE quiz_name='".$name."'";
	$array = mysqli_query($mysqli, $added);
	

	$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

	$userRating = $_POST['user_rating']; 

	foreach ($x as $val) {
		$addQuiz = "INSERT INTO tbactivities (for_quiz,user_id,score,out_of,user_rating) VALUES('".$val["quiz_id"]."','".$_COOKIE["user"]."','".$userScore."','".$from."','".$userRating."')";
	}	

	if (mysqli_query($mysqli, $addQuiz)){
		echo json_encode(true); 
	}
	else {
		echo json_encode(false); 
	}
?>