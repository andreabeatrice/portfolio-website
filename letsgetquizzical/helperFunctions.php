<?php 
		
		include 'conn.php';

		function sortByTime($a, $b) {

			$timea = strtotime($a['creation_time']);
			$timeb = strtotime($b['creation_time']);

			   return strcmp($timea,$timeb);
		}

		function lastUpdated($dt) {
			echo 'Last updated ';
			
			$datetime1 = $dt;//start time
			$datetime2 = new DateTime();//end time
			$interval = $datetime1->diff($datetime2);

			if ($interval->format('%Y') != 0){
				echo $interval->format('%Y years');

			}
			else if ($interval->format('%m') != 0){
				echo $interval->format('%m months');
			}
			else if ($interval->format('%d') != 0){
				echo $interval->format('%d days');
			}
			else if ($interval->format('%H') != 0){
				echo $interval->format('%H hours');
			}
			else if ($interval->format('%i') != 0){
				echo $interval->format('%i minutes');
			}
			else {
				echo $interval->format('%s seconds');
			}
			
			echo ' ago.';
		}

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
		            "email" => $val["email_address"]
		         );

				$uId = $val["user_id"];
				$name = $val["first_name"];
				$surname = $val["last_name"];
				$email = $val["email_address"];
				$date = $val["date_of_birth"];
				$pass = $val["password"];
				setcookie("user", $uId, time() + (86400 * 30), "/"); 
				//echo $_COOKIE["user"];

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
		            "password" => $val["password"]
		         );
			}

			return $userInfo;

		}


?>