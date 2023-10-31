<?php
	include 'conn.php';

	//FRIENDS FUNCTIONALITY - SEARCH TERM
		if (isset($_GET['term'])){
			$searchTerm = $_GET['term']; 
			 
			$added = "SELECT * FROM tbaccounts";
			$a = mysqli_query($mysqli, $added);
			$x = mysqli_fetch_all($a, MYSQLI_ASSOC);
			$numQ = 0;
			$quizData = array(); 

			foreach (array_reverse($x) as $val) {
				if (stripos($val['email_address'], $searchTerm) !== false) {
			    	array_push($quizData, strtok($val['email_address'], '@')); 
				}
			}

			echo json_encode($quizData); 
		}

	//FRIENDS FUNCTIONALITY - FRIEND REQUEST
		else if (isset($_POST['requestType'])){
			$sent = false;
			$sentFrom = $_COOKIE["user"]; 
			$sentTo = $_POST['friendship_acceptor'];

			$userRequest = "SELECT user_id FROM tbaccounts WHERE email_address LIKE '%".$sentTo."%'";
			$a = mysqli_query($mysqli, $userRequest);
			$x = mysqli_fetch_all($a, MYSQLI_ASSOC);

			foreach ($x as $val) {
				$isSent = "SELECT * FROM tbfriends WHERE friendship_creator='".$sentFrom."' AND friendship_acceptor='".$val['user_id']."' ";
				$already = mysqli_query($mysqli, $isSent);
				$key = mysqli_fetch_all($already, MYSQLI_ASSOC);

				foreach ($key as $y) {
					$sent = true;
				}

				if($sent == false){
					$friendReq = "INSERT INTO tbfriends (friendship_creator, friendship_acceptor) VALUES ('$sentFrom', '".$val['user_id']."')";
					$res = mysqli_query($mysqli, $friendReq) == TRUE;
					if($res){

					}
				}
			}
		}
?>