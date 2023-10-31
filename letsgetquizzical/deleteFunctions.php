<?php 
	include 'conn.php';


	if (isset($_POST['type'])) {
		if (strcmp($_POST['type'], "deleteActivity") == 0) {
			$del = "DELETE FROM `tbactivities` WHERE for_quiz='".$_POST['for_quiz']."' AND user_id='".$_POST['from_user']."';";

			if (mysqli_query($mysqli, $del)){
				echo json_encode($del); 
			}
			else {
				echo json_encode($del); 
			}
		}

		if (strcmp($_POST['type'], "deleteQuiz") == 0) {
			$del = "DELETE FROM `tbquizzes` WHERE quiz_id='".$_POST['for_quiz']."'";

			if (mysqli_query($mysqli, $del)){
				header('Location: home.php');
				exit;
			}
			else {
				echo json_encode($del); 
			}
		}

		if (strcmp($_POST['type'], "deleteFriendship") == 0) {
			$sentTo = $_POST['unfriend'];
			$del ="";

			$userRequest = "SELECT user_id FROM tbaccounts WHERE email_address LIKE '%".$sentTo."%'";
			$a = mysqli_query($mysqli, $userRequest);
			$x = mysqli_fetch_all($a, MYSQLI_ASSOC);

			foreach ($x as $val) {
				$delRequest = "SELECT * FROM tbfriends WHERE friendship_creator='".$_COOKIE['user']."' OR friendship_acceptor='".$_COOKIE['user']."'";
				$allFriendships = mysqli_query($mysqli, $delRequest);
				$array = mysqli_fetch_all($allFriendships, MYSQLI_ASSOC);

				foreach ($array as $key) {
					if(strcmp($key['friendship_acceptor'], $_COOKIE['user']) == 0){
						$del = "DELETE FROM `tbfriends` WHERE friendship_creator='".$val['user_id']."' AND friendship_acceptor='".$_COOKIE['user']."';";
					}else {
						$del = "DELETE FROM `tbfriends` WHERE friendship_acceptor='".$val['user_id']."' AND friendship_creator='".$_COOKIE['user']."';";
					}
				}

				if (mysqli_query($mysqli, $del)){
					echo json_encode($del); 
				}
				else {
					echo json_encode($del); 
				}
			}
		}
	}




?>