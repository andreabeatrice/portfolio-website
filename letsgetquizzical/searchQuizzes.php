<?php
	include 'logged-in.php' ;
	include 'conn.php' ;
	// Get search term 
	$searchTerm = $_GET['term']; 
	 
	$added = "SELECT * FROM tbquizzes";
	$a = mysqli_query($mysqli, $added);
	$x = mysqli_fetch_all($a, MYSQLI_ASSOC);
	$numQ = 0;
	$quizData = array(); 

	foreach (array_reverse($x) as $val) {
		if (stripos($val['quiz_name'], $searchTerm) !== false) {
	    	array_push($quizData, $val['quiz_name']); 
		}
	}

	echo json_encode($quizData); 

?>