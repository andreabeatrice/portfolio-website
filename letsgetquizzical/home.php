<?php
	if(TRUE)// toggle to false after debugging
	{
	  ini_set( 'display_errors', 'true');
	  error_reporting(-1);
	}

	$name = "";
	$surname = "";
	$email = "";
	$date = "";
	$pass = "";
	$validlogin = false;
	$emailOrPass = "";
	
	$quizAdded = false;
	$unseen = false;
	$userInfo = array();

	include 'conn.php';

	

	//LOGIN
		if (isset($_POST['login'])){
			//echo $_COOKIE['EMAIL'];

			$email = mysqli_real_escape_string($mysqli, $_POST['email']);
			$password = mysqli_real_escape_string($mysqli, $_POST['pass']);

			$added = "SELECT * FROM tbaccounts";
			$array = mysqli_query($mysqli, $added);
			$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

			foreach ($x as $val) {
				if(strcmp($email,$val['email_address']) == 0){
					$rightpass = "SELECT * FROM tbaccounts WHERE email_address='$email'";

					$db_password = mysqli_query($mysqli, $rightpass);

					$j = mysqli_fetch_all($db_password, MYSQLI_ASSOC);

					foreach ($j as $v) {
						if(strcmp($password ,$v['password']) == 0){
							$validlogin = true;
							
							$name = $v["first_name"];
							$surname = $v["last_name"];
							$email = $v["email_address"];
							$date = $v["date_of_birth"];
							$pass = $v["password"];

						}
						else {
							$emailOrPass = "password";
						}
					}
				}
				else {
					$emailOrPass = "email";
				}

			}

			if (!$validlogin) {
				header("Location: index.php?error=".$emailOrPass);
				die();
							
			}

		}
	
	//REGISTRATION
		else if (isset($_POST['register'])){
			echo 'register';
			//$mysqli = mysqli_connect("localhost", "root", "", "dbquizzical");

			$name = $_POST["fname"];
			$surname = $_POST["lname"];
			$email = $_POST["email"];
			$date = $_POST["date"];
			$password = $_POST["pass"];

			$query = "INSERT INTO tbaccounts (first_name, last_name, email_address, date_of_birth, password) VALUES ('$name', '$surname', '$email', '$date', '$password');";

			$res = mysqli_query($mysqli, $query) == TRUE;

			if($res){
				$userInfo = loggedIn($email, $password, $mysqli)[0];
				header("location: {$_SERVER['PHP_SELF']}");
			}
		}

	//CALL LOGGEDIN (SET user COOKIE)
		if (isset($_POST['register']) || isset($_POST['login'])){
			//include('logged-in.php');
			$userInfo = loggedIn($email, $password, $mysqli)[0];
			header("location: {$_SERVER['PHP_SELF']}");
		}
		else {
			//include('logged-in.php');
			$userInfo = li($mysqli)[0];
		}

	//SET NOTIFICATION FOR INBOX
		$nC = "SELECT * FROM tbchat WHERE sent_to='".$_COOKIE["user"]."'";
		$isThere = mysqli_query($mysqli, $nC);
		$change = mysqli_fetch_all($isThere, MYSQLI_ASSOC);
		$n = 0;

		foreach ($change as $s) {
			if($s['seen'] == 0){
				$unseen = true;
				$n++;
			}
		}
	
	//FILE UPLOAD VARIABLES
		$outFile = ''; //output file error
		$outDesc = ''; //output quiz desc. error
		$uploadOk = 1; // for checking errors
		$outUp = ''; //output upload error
		$target_dir = "gallery/";
		$origN;

	//NEW QUIZ SUBMITTED
		if (isset($_POST["submitQuiz"])) {
			if (isset($_POST["quizName"]) && $_POST["quizName"] != "") {
				$quizN = mysqli_real_escape_string($mysqli, $_POST['quizName']);
				//$quizN = $_POST["quizName"];
				$outDesc = "";
				//$uploadOk = 1;
			}
			else{
				$outDesc = "*Required";
				$uploadOk = 0;
			}

			if (isset($_POST["quizDescription"]) && $_POST["quizDescription"] != "") {
				$quizD = mysqli_real_escape_string($mysqli, $_POST['quizDescription']);
				$outDesc = "";
				//$uploadOk = 1;
			}
			else{
				$outDesc = "*Required";
				$uploadOk = 0;
			}

			if (isset($_POST["quizTags"]) && $_POST["quizTags"] != "") {
				$quizT = mysqli_real_escape_string($mysqli, $_POST['quizTags']);
				$outDesc = "";
				//$uploadOk = 1;
			}
			else{
				$outDesc = "*Required";
				$uploadOk = 0;
			}

			$quizC = mysqli_real_escape_string($mysqli, $_POST['quizCategory']);

			foreach ($_FILES["quizIconToUpload"]["error"] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["quizIconToUpload"]["tmp_name"][$key];
					$target_file = $target_dir . basename($_FILES["quizIconToUpload"]["name"][$key]);
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

					//check that the file is < 1MB
					if ($_FILES["quizIconToUpload"]["size"][$key] >= 1000000) {
						$outFile = "File must be smaller than 1MB!";
						$uploadOk = 0;
					}
					else {
						$outFile = "";
						$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
						$detectedType = exif_imagetype($_FILES['quizIconToUpload']['tmp_name'][$key]);
						$error = !in_array($detectedType, $allowedTypes);
						//if the file is <1MB, check that it's a JPG
						if($error) {
							$outFile = "Sorry, only JPG/JPEG files are allowed.";
							$uploadOk = 0;
						}
						else {
							$outFile = "";
							//everything is valid :)
						}
					}
				}
				else {
					$uploadOk = 0;
				}


				if ($uploadOk != 0) {
					if (!file_exists($target_dir)) {
					    mkdir($target_dir, 0777, true);
					}
			
					if (move_uploaded_file($_FILES["quizIconToUpload"]["tmp_name"][$key], $target_file)) {

						$in = basename( $_FILES["quizIconToUpload"]["name"][$key]);
						//Add quiz to database
					   $added = "SELECT * FROM tbaccounts WHERE email_address='".$userInfo["email"]."' AND password='".$userInfo["password"]."'";
						$arr = $mysqli->query($added);

						if($row = mysqli_fetch_array($arr)){
							//echo "quiz added";
							$addQuiz = "INSERT INTO tbquizzes(user_id,quiz_name,quiz_description,quiz_tags,quiz_category) VALUES('".$row['user_id']."','".$quizN."','".$quizD."','".$quizT."','".$quizC."')";
							
							if (mysqli_query($mysqli, $addQuiz)){

								$newQ = "SELECT * FROM tbquizzes WHERE quiz_name='".$quizN."' AND user_id='".$row['user_id']."'";
								$a = mysqli_query($mysqli, $newQ);
								$row2 = mysqli_fetch_all($a, MYSQLI_ASSOC);

								foreach ($row2 as $s) {
									$addGallery = "INSERT INTO tbgallery(quiz_id,image_name) VALUES('".$s['quiz_id']."','$in')";
									if (mysqli_query($mysqli, $addGallery)){
										$quizAdded = true;
									}

								}
							}
							
						}

					}
		
				}
				else {
						$added = "SELECT * FROM tbaccounts WHERE email_address='".$userInfo["email"]."' AND password='".$userInfo["password"]."'";
						$arr = $mysqli->query($added);

						if($row = mysqli_fetch_array($arr)){
							$quizN = str_replace( "\"", "”", $quizN);

							$quizN = str_replace( "'", "’", $quizN);

							//echo "quiz added";
							$addQuiz = "INSERT INTO tbquizzes(user_id,quiz_name,quiz_description,quiz_tags,quiz_category) VALUES('".$row['user_id']."','".$quizN."','".$quizD."','".$quizT."','".$quizC."')";

							if (mysqli_query($mysqli, $addQuiz)){
								$newQ = "SELECT * FROM tbquizzes WHERE quiz_name='".$quizN."' AND user_id='".$row['user_id']."'";
								$a = mysqli_query($mysqli, $newQ);
								$row2 = mysqli_fetch_all($a, MYSQLI_ASSOC);

								foreach ($row2 as $s) {
									$addGallery = "INSERT INTO tbgallery(quiz_id,image_name) VALUES('".$s['quiz_id']."','default.jpg')";
									if (mysqli_query($mysqli, $addGallery)){
										$quizAdded = true;
									}

								}
							}
						}

				}
			}

			if($quizAdded){

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$whatToStrip = array("?","!",",",";"," ","-", ":");
					$n = str_replace($whatToStrip, "", $_POST["quizName"]);

					$n = str_replace( "\"", "”", $n);

					$n = str_replace( "'", "’", $n);
		          
			      function get_data() {
			        	$numQs = 0;
			         $datae = array();
			         $datae[] = array(
			            "QuizName" => $_POST["quizName"],
			            "QuizDescription" => $_POST["quizDescription"],
			            "QuizTags" => $_POST["quizTags"],
			         );

			         foreach ($_POST as $key => $value) {
			            if (strpos($key, 'questionName') !== false) {
			            	$numQs++;
			            }
			         }


			      	for ($i=1; $i <= $numQs; $i++) {	
			            $str = 'questionName'.$i;
				         $qStr = 'question'.$i.'Answer';
				         $lessonStr = 'question'.$i.'Lesson';
				         $lIstr =  'question'.$i.'LessonImage';
				            //question${qNum-1}Lesson
				         $j = 1;
				         $innerArr = array(
				           	"QuestionName" => $_POST[$str]
				         );
				         
				         foreach ($_POST as $key => $value) {
				            if (strpos($key, $qStr) !== false) {
				            	$ansStr = $j;
				            	$innerArr[$ansStr] = $value;
						         $j++;
				            }
				         }
				            
				         $innerArr["Correct"] = $_POST["question".$i."RightAnswer"];
				         $innerArr["numAnswers"] = $_POST["numAns".$i];

				         $innerArr["Lesson"] = str_replace("\n","<br />",$_POST[$lessonStr]);
				         
				         //LESSON IMAGE
					         $target_dir = "gallery/";
								$tmp_name = $_FILES[$lIstr]["tmp_name"];
								$target_file = $target_dir . basename($_FILES[$lIstr]["name"]);
								$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
						        
						    if (move_uploaded_file($_FILES[$lIstr]["tmp_name"], $target_file)) {
								$in = basename( $_FILES[$lIstr]["name"]);

								$innerArr["LessonImage"] = $in;
							}
								else {
									$innerArr["LessonImage"] = "";
								}

				         array_push($datae, $innerArr);
						          
			         }
			            
			         return json_encode($datae, JSON_PRETTY_PRINT);
			      }
		          
		        $name = $n;
		       
		        $target_dir_quiz = "quizzes/";

		        	if (!file_exists($target_dir_quiz)) {
						mkdir($target_dir_quiz, 0777, true);
					}

				 	$file_name = $target_dir_quiz. $name . '.json';


		        	if(file_put_contents("$file_name", get_data())) {
		            //echo $file_name .' file created';
		        	}
		        	else {
		           	// echo 'There is some error';
		        	}
	    		} //$_SERVER['REQUEST_METHOD']
			}
		}

	//NEW LIST CREATED
		if (isset($_POST["submitList"])){
			$added = "SELECT * FROM tbaccounts WHERE email_address='".$userInfo["email"]."' AND password='".$userInfo["password"]."'";
			$arr = $mysqli->query($added);

			if($row = mysqli_fetch_array($arr)){
				//echo "quiz added"; list_id	list_name		list_owner
				$addList = "INSERT INTO tblists(list_name,list_description,list_tags,list_quizzes,list_owner) VALUES('".$_POST["listName"]."','".$_POST["listDescription"]."','".$_POST["listTags"]."','".$_POST["qList"]."','".$_COOKIE["user"]."')";
				mysqli_query($mysqli, $addList);
			}
		}

	//HELPER FUNCTIONS
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

	//QUIZ EDITED 
		if (isset($_POST["editQuiz"])){
			$select = "SELECT * FROM tbquizzes WHERE quiz_id='".$_POST['quiz_id']."'";
			$disp = $mysqli->query($select);

			if($r = mysqli_fetch_array($disp)){
				if (isset($_POST["quizName"]) && $_POST["quizName"] != "") {
					$quizN = mysqli_real_escape_string($mysqli, $_POST['quizName']);
					$outDesc = "";
					$origN = $r['quiz_name'];
				}
				else {
					$_POST["quizName"] = $r['quiz_name'];
					$quizN = $r['quiz_name'];
					$origN = $r['quiz_name'];
				}

				if (isset($_POST["quizDescription"]) && $_POST["quizDescription"] != "") {
					$quizD = mysqli_real_escape_string($mysqli, $_POST['quizDescription']);
					$outDesc = "";
				}
				else {
					$_POST["quizDescription"] = $r['quiz_description'];
					$quizD = $r['quiz_description'];
				}

				if (isset($_POST["quizTags"]) && $_POST["quizTags"] != "") {
					$quizT = mysqli_real_escape_string($mysqli, $_POST['quizTags']);
					$outDesc = "";
				}
				else {
					$_POST["quizTags"] = $r['quiz_tags'];
					$quizT = $r['quiz_tags'];
				}

				if (isset($_POST["quizCategory"]) && $_POST["quizCategory"] != "") {
					$quizC = mysqli_real_escape_string($mysqli, $_POST['quizCategory']);
					$outDesc = "";
				}
				else {
					$_POST["quizCategory"] = $r['quiz_category'];
					$quizC = $r['quiz_category'];
				}
			}

			if (empty($_FILES['uploadNewQuizIcon']['name'])){

			}

			else{

					$target_dir = "gallery/"; //directory of files to placed 
					$uploadFile = $_FILES['uploadNewQuizIcon'];//file being uploaded
					$target_file = $target_dir . basename($uploadFile["name"]); // path of file to be uploaded
					$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);//holds file extnesion type
					$imageTypeAllowed = array('jpeg', 'jpg','png');
					$imageName = $uploadFile["name"];//file name and extension
					$fileNameTemp = $uploadFile["tmp_name"];//temp file name and extension

					move_uploaded_file($fileNameTemp,"gallery/" . $imageName);

					$sql = "UPDATE tbgallery SET image_name='".$imageName."' WHERE quiz_id='".$_POST['quiz_id']."'";

					if ($mysqli->query($sql) === TRUE) {
					
					} 
					else {
						//echo "Error updating record: " . $mysqli->error;
					}
				}

			$added = "SELECT * FROM tbaccounts WHERE email_address='".$userInfo["email"]."' AND password='".$userInfo["password"]."'";
			$arr = $mysqli->query($added);

			if($row = mysqli_fetch_array($arr)){
				$quizN = str_replace( "\"", "”", $quizN);
				$quizN = str_replace( "'", "’", $quizN);

				$quizD = str_replace( "\"", "”", $quizD);
				$quizD = str_replace( "'", "’", $quizD);

				$date = date("Y-m-d H:i:s"); 
				$addQuiz = "UPDATE tbquizzes SET quiz_name='".$quizN."', quiz_description='".$quizD."', quiz_tags='".$quizT."', quiz_category='".$quizC."', creation_time='".$date."' WHERE quiz_id='".$_POST['quiz_id']."'";

				if (mysqli_query($mysqli, $addQuiz)){
					$quizAdded = true;
				}
				else {
					echo mysqli_error($mysqli);
				}
			}
			

			if($quizAdded){
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$whatToStrip = array("?","!",",",";"," ","-", ":");
					$n = str_replace($whatToStrip, "", $_POST["quizName"]);

					$n = str_replace( "\"", "”", $n);

					$n = str_replace( "'", "’", $n);
		          
			      	function get_data($n) {

			      		$whatToStrip = array("?","!",",",";"," ","-", ":");
						$n = str_replace($whatToStrip, "", $n);

						$n = str_replace( "\"", "”", $n);

						$n = str_replace( "'", "’", $n);

			      		$nfname = 'quizzes/'.$n.'.json';
						$filedata = file_get_contents($nfname);
						$details = json_decode($filedata, JSON_PRETTY_PRINT);

			        	$numQs = 0;
			        	$datae = array();
			        	$datae[] = array(
			            	"QuizName" => $_POST["quizName"],
			            	"QuizDescription" => $_POST["quizDescription"],
			            	"QuizTags" => $_POST["quizTags"],
			         	);

			         	foreach ($_POST as $key => $value) {
			            	if (strpos($key, 'questionName') !== false) {
			            		$numQs++;
			            	}
			         	}

				      	for ($i=1; $i <= $numQs; $i++) {	
				            $str = 'questionName'.$i;
					        $qStr = 'question'.$i.'Answer';
					        $lessonStr = 'question'.$i.'Lesson';
					        $lIstr =  'question'.$i.'LessonImage';

					        $j = 1;
					        $innerArr = array(
					        	"QuestionName" => $_POST[$str]
					        );
					         
					        foreach ($_POST as $key => $value) {
					        	if (strpos($key, $qStr) !== false) {
					            	$ansStr = $j;
					            	$innerArr[$ansStr] = $value;
							        $j++;
					            }
					        }
					            
					        $innerArr["Correct"] = $_POST["question".$i."RightAnswer"];
					        $innerArr["numAnswers"] = $_POST["numAns".$i];

					        $innerArr["Lesson"] = str_replace("\n","<br />",$_POST[$lessonStr]);
					         
					        //LESSON IMAGE
						        $target_dir = "gallery/";
								$tmp_name = $_FILES[$lIstr]["tmp_name"];
								$target_file = $target_dir . basename($_FILES[$lIstr]["name"]);
								$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
							        
							    if (move_uploaded_file($_FILES[$lIstr]["tmp_name"], $target_file)) {
									$in = basename( $_FILES[$lIstr]["name"]);
									$innerArr["LessonImage"] = $in;
								}
								else if (isset($details[$i]["LessonImage"])){
									$innerArr["LessonImage"] = $details[$i]["LessonImage"];
								}
								else {
									$innerArr["LessonImage"] = "";
								}

					        array_push($datae, $innerArr);
							          
				        }
				            
				        return json_encode($datae, JSON_PRETTY_PRINT);
				    }
		          
		        	$name = $n;
		       
		        	$target_dir_quiz = "quizzes/";

		        	if (!file_exists($target_dir_quiz)) {
						mkdir($target_dir_quiz, 0777, true);
					}

				 	$file_name = $target_dir_quiz. $name . '.json';


		        	if(file_put_contents("$file_name", get_data($origN))) {
		        	
		        	}
		        	else {
		           		// echo 'There is some error';
		        	}
	    		}
			}
		}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>let's get quizzical</title>
		
		<?php require 'head.php'; ?>

	</head>
	<body id="body">
		<?php require 'header.php'; ?>

 		<div class="container mt-3" id="homeQuizList">
			<?php 
				$u = $_SERVER["REQUEST_URI"];
			
			//GLOBAL FEED DISPLAY
				if (strpos($u, 'global') !== false) {
					$quizListQuery = "SELECT * FROM tbquizzes";
					$answer = mysqli_query($mysqli, $quizListQuery);
					$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);

					$listListQuery = "SELECT * FROM tblists";
					$an = mysqli_query($mysqli, $listListQuery);
					$retL = mysqli_fetch_all($an, MYSQLI_ASSOC);

					foreach ($retL as $key) {
						array_push($returned, $key);
					}

					$activityListQuery = "SELECT * FROM tbactivities";
					$aa = mysqli_query($mysqli, $activityListQuery);
					$retA = mysqli_fetch_all($aa, MYSQLI_ASSOC);

					foreach ($retA as $key) {
						array_push($returned, $key);
					}

					usort($returned, 'sortByTime'); 

					foreach (array_reverse($returned) as $key) {
						//DISPLAY GLOBAL QUIZZES
							if(isset($key["quiz_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								$quizImageQuery = "SELECT * FROM tbgallery WHERE quiz_id='".$key['quiz_id']."'";
								$ImgArray = $mysqli->query($quizImageQuery);

								if($image = mysqli_fetch_array($ImgArray)){
									echo "<img src='gallery/".$image['image_name']."' class='tagsQuizImage' alt='...'>";
								}

								echo '<a class="quizLink" href="quizPage.php?quiz=';

								$whatToStrip = array("!",",",";"," ", "\"");
								$n = str_replace($whatToStrip, "-", $key['quiz_name']);
								
								echo $n.'">';
								echo "<h3 class='card-title'>".$key['quiz_name']."</h3></a>";
								echo "<p class='card-text-home'>".$key['quiz_description']."</p>";

								//TAGS
									$tagArray = explode(',', $key['quiz_tags']);
									foreach ($tagArray as $k) {
										echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
										echo '&ensp;';
									}
								

								$quizMakerQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['user_id']."'";
								$makerArray = $mysqli->query($quizMakerQuery);

								if($owner = mysqli_fetch_array($makerArray)){
									echo "</div> <div class='card-footer bg-transparent border-transparent'><p style='text-align: right;'class='card-text float-right'><small class='text-muted'><a href='profile.php?user=".$owner['user_id']."&view=quizzes' class='card-link'><span class='icon profile mr-1'></span>".strtok($owner['email_address'],  '@')."</a><br/> <i class='fas fa-history mr-1'></i>";
							
									lastUpdated(new DateTime($key['creation_time']));

									echo "</small></p></div>";
								}

								echo '</div>';
							}
					
						//DISPLAY GLOBAL LISTS
							else if(isset($key["list_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								  
								$tA = explode(',', $key['list_quizzes']);
								$imArr = array();

								foreach ($tA as $k) {
									$quizImageQuery = "SELECT * FROM tbquizzes WHERE quiz_name='".$k."'";
									$ImgArray = $mysqli->query($quizImageQuery);

									if($image = mysqli_fetch_array($ImgArray)){
										$iiQ = "SELECT * FROM tbgallery WHERE quiz_id='".$image['quiz_id']."'";
										$iA = $mysqli->query($iiQ);

										if($im = mysqli_fetch_array($iA)){
											array_push($imArr, $im['image_name']);		
										}
									}
								}

								$i = 0;
								echo '<div class="lCover-large">';
									foreach ($imArr as $k) {
										if($i < 4){
											echo "<img src='gallery/".$k."' width='50%' alt='...'>";
											if ($i % 2 != 0){
												echo '<br/>';
											}
											$i++;
										}
									}
									if($i < 4){
										foreach (array_reverse($imArr) as $k) {
											if($i < 4){
												echo "<img src='gallery/".$k."' width='50%' alt='...'>";
												if ($i % 2 != 0 && $i!=3){
													echo '<br/>';
												}
												$i++;
											}
										}
									}
									
								echo '</div>';
								
								echo '<div class="l-info-large">';
								echo "<h3 class='card-title'>".$key['list_name']."</h3>";
								echo "<p class='card-text-home'>".$key['list_description']."</p>";
								echo '<a href="listPage.php?list=';

								$whatToStrip = array("!",",",";"," ", "\"");
								$n = str_replace($whatToStrip, "-", $key['list_name']);
								echo $n;

								echo '" class="btn ">See List</a>';
								echo '</div>';

								$quizMakerQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['list_owner']."'";
								$makerArray = $mysqli->query($quizMakerQuery);

								if($owner = mysqli_fetch_array($makerArray)){
									echo "</div> <div class='card-footer bg-transparent border-transparent'><p style='text-align: right;'class='card-text float-right'><small class='text-muted'><span class='icon profile mr-1'></span>".strtok($owner['email_address'],  '@')."<br/> <i class='fas fa-history mr-1'></i>";
							
									lastUpdated(new DateTime($key['creation_time']));

									echo "</small></p></div>";
								}
						
								echo "</div>";
							}
					
						//DISPLAY GLOBAL ACTIVITIES
							else if(isset($key["activity_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								$userInfoQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['user_id']."'";
								$userInfo = $mysqli->query($userInfoQuery);

								if($k = mysqli_fetch_array($userInfo)){
									$quizExists = FALSE;
									echo '<div class="profile-pic float-left mr-1" id="profileIcon" style="background-image: url(gallery/'.$k['user_image'].')" alt="..."></div>';

									$quizInfo = $mysqli->query("SELECT * FROM tbquizzes WHERE quiz_id='".$key['for_quiz']."'");
									if($q = mysqli_fetch_array($quizInfo)){
										$quizExists = TRUE;
										if (strcmp($key['user_id'], $_COOKIE["user"])==0) {
											echo '<h6 class="card-title mt-3" id="userName">You completed ';
										}
										else {
											echo '<h6 class="card-title mt-3" id="userName">'.strtok($k['email_address'],  '@').' completed ';
										}
								
										echo '<a class="quizLink" href="quizPage.php?quiz=';

										$whatToStrip = array("!",",",";"," ", "\"");
										$n = str_replace($whatToStrip, "-", $q['quiz_name']);
										echo $n.'">';

										echo $q['quiz_name'].'</a> with '.$key['score'].'/'.$key['out_of'].'</h6>';
									}

									if ($quizExists != TRUE) {
										if (strcmp($key['user_id'], $_COOKIE["user"])==0) {
											echo '<h6 class="card-title mt-3" id="userName">You completed ';
										}
										else {
											echo '<h6 class="card-title mt-3" id="userName">'.strtok($k['email_address'],  '@').' completed ';
										}

										echo 'a deleted quiz.';
									}
								}	

						

								echo '</div></div>';
							}
					}
				}
			
			//LOCAL FEED DISPLAY
				else {
					$allActivities = [];

					//GET ALL QUIZZES MADE BY FRIENDS
						$friendsListQuery = "SELECT * FROM tbfriends WHERE friendship_creator='".$_COOKIE["user"]."' AND friendship_accepted='1'";
						$friendsAnswer = mysqli_query($mysqli, $friendsListQuery);
						$friendsList = mysqli_fetch_all($friendsAnswer, MYSQLI_ASSOC);

						foreach ($friendsList as $key) {
							$friendQuizQuery = "SELECT * FROM tbquizzes WHERE user_id='".$key['friendship_acceptor']."'";
							$answer = mysqli_query($mysqli, $friendQuizQuery);
							$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);
							foreach ($returned as $k) {
								array_push($allActivities, $k);
							}
						}
						
						$friendsListQuery = "SELECT * FROM tbfriends WHERE friendship_acceptor='".$_COOKIE["user"]."' AND friendship_accepted='1'";
						$friendsAnswer = mysqli_query($mysqli, $friendsListQuery);
						$friendsList = mysqli_fetch_all($friendsAnswer, MYSQLI_ASSOC);

						foreach ($friendsList as $key) {
							$friendQuizQuery = "SELECT * FROM tbquizzes WHERE user_id='".$key['friendship_creator']."'";
							$answer = mysqli_query($mysqli, $friendQuizQuery);
							$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);
							foreach ($returned as $k) {
								array_push($allActivities, $k);
							}		
						}

					//GET ALL ACTIVITIES BY FRIENDS
						$friendsListQuery = "SELECT * FROM tbfriends WHERE friendship_creator='".$_COOKIE["user"]."' AND friendship_accepted='1'";
						$friendsAnswer = mysqli_query($mysqli, $friendsListQuery);
						$friendsList = mysqli_fetch_all($friendsAnswer, MYSQLI_ASSOC);

						foreach ($friendsList as $key) {
							$friendQuizQuery = "SELECT * FROM tbactivities WHERE user_id='".$key['friendship_acceptor']."'";
							$answer = mysqli_query($mysqli, $friendQuizQuery);
							$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);
							foreach ($returned as $k) {
								array_push($allActivities, $k);
							}
						}

						$friendsListQuery = "SELECT * FROM tbfriends WHERE friendship_acceptor='".$_COOKIE["user"]."' AND friendship_accepted='1'";
						$friendsAnswer = mysqli_query($mysqli, $friendsListQuery);
						$friendsList = mysqli_fetch_all($friendsAnswer, MYSQLI_ASSOC);

						foreach ($friendsList as $key) {
							$friendQuizQuery = "SELECT * FROM tbactivities WHERE user_id='".$key['friendship_creator']."'";
							$answer = mysqli_query($mysqli, $friendQuizQuery);
							$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);
							foreach ($returned as $k) {
								array_push($allActivities, $k);
							}		
						}

					//GET ALL QUIZZES MADE BY USER
						$quizListQuery = "SELECT * FROM tbquizzes WHERE user_id='".$_COOKIE["user"]."'";
						$answer = mysqli_query($mysqli, $quizListQuery);
						$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);
						foreach ($returned as $k) {
							array_push($allActivities, $k);
						}

					//GET ALL LISTS MADE BY USER
						$listListQuery = "SELECT * FROM tblists WHERE list_owner='".$_COOKIE["user"]."'";
						$an = mysqli_query($mysqli, $listListQuery);
						$retL = mysqli_fetch_all($an, MYSQLI_ASSOC);

						foreach ($retL as $key) {
							array_push($allActivities, $key);
						}

					//GET ALL ACTIVITIES BY USER
						$activityListQuery = "SELECT * FROM tbactivities WHERE user_id='".$_COOKIE["user"]."'";
						$aa = mysqli_query($mysqli, $activityListQuery);
						$retA = mysqli_fetch_all($aa, MYSQLI_ASSOC);

						foreach ($retA as $key) {
							array_push($allActivities, $key);
						}
			

					usort($allActivities, 'sortByTime'); 

					foreach (array_reverse($allActivities) as $key) {
						//DISPLAY LOCAL QUIZZES
							if(isset($key["quiz_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								$quizImageQuery = "SELECT * FROM tbgallery WHERE quiz_id='".$key['quiz_id']."'";
								$ImgArray = $mysqli->query($quizImageQuery);

								if($image = mysqli_fetch_array($ImgArray)){
									echo "<img src='gallery/".$image['image_name']."' class='tagsQuizImage' alt='...'>";
								}

								echo '<a class="quizLink" href="quizPage.php?quiz=';
								$whatToStrip = array("!",",",";"," ", "\"");
								$n = str_replace($whatToStrip, "-", $key['quiz_name']);
								echo $n.'">';

								echo "<h3 class='card-title'>".$key['quiz_name']."</h3></a>";
								echo "<p class='card-text-home'>".$key['quiz_description']."</p>";

								//TAGS
									$tagArray = explode(',', $key['quiz_tags']);
									foreach ($tagArray as $k) {
										echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
										echo '&ensp;';
									}
									

								$quizMakerQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['user_id']."'";
								$makerArray = $mysqli->query($quizMakerQuery);

								if($owner = mysqli_fetch_array($makerArray)){
									echo "</div> <div class='card-footer bg-transparent border-transparent'><p style='text-align: right;'class='card-text float-right'>";

									echo "<small class='text-muted'><a href='profile.php?user=".$owner['user_id']."&view=quizzes' class='card-link'><span class='icon profile mr-1'></span>".strtok($owner['email_address'],  '@')."</a><br/> <i class='fas fa-history mr-1'></i>";
								
									lastUpdated(new DateTime($key['creation_time']));

									echo "</small></p></div>";
								}

								echo '</div>';
							}

						//DISPLAY LOCAL LISTS
							else if(isset($key["list_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								  
								$tA = explode(',', $key['list_quizzes']);
								$imArr = array();

								foreach ($tA as $k) {
									$quizImageQuery = "SELECT * FROM tbquizzes WHERE quiz_name='".$k."'";
									$ImgArray = $mysqli->query($quizImageQuery);

									if($image = mysqli_fetch_array($ImgArray)){
										$iiQ = "SELECT * FROM tbgallery WHERE quiz_id='".$image['quiz_id']."'";
										$iA = $mysqli->query($iiQ);

										if($im = mysqli_fetch_array($iA)){
											array_push($imArr, $im['image_name']);		
										}
									}
								}

								$i = 0;
								echo '<div class="lCover-large">';
									foreach ($imArr as $k) {
										if($i < 4){
											echo "<img src='gallery/".$k."' width='50%' alt='...'>";
											if ($i % 2 != 0){
												echo '<br/>';
											}
											$i++;
										}
									}
									if($i < 4){
										foreach (array_reverse($imArr) as $k) {
											if($i < 4){
												echo "<img src='gallery/".$k."' width='50%' alt='...'>";
												if ($i % 2 != 0 && $i!=3){
													echo '<br/>';
												}
												$i++;
											}
										}
									}
									
								echo '</div>';
								
								echo '<div class="l-info-large">';
								echo "<h3 class='card-title'>".$key['list_name']."</h3>";
								echo "<p class='card-text-home'>".$key['list_description']."</p>";
								echo '<a href="listPage.php?list=';

								$whatToStrip = array("!",",",";"," ", "\"");
								$n = str_replace($whatToStrip, "-", $key['list_name']);
								echo $n;

								echo '" class="btn ">See List</a>';
								echo '</div>';

								$quizMakerQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['list_owner']."'";
								$makerArray = $mysqli->query($quizMakerQuery);

								if($owner = mysqli_fetch_array($makerArray)){
									echo "</div> <div class='card-footer bg-transparent border-transparent'><p style='text-align: right;'class='card-text float-right'><small class='text-muted'><span class='icon profile mr-1'></span>".strtok($owner['email_address'],  '@')."<br/> <i class='fas fa-history mr-1'></i>";
							
									lastUpdated(new DateTime($key['creation_time']));

									echo "</small></p></div>";
								}
						
								echo "</div>";
							}

						//DISPLAY LOCAL ACTIVITIES
							else if(isset($key["activity_id"])){
								echo "<div class='card mb-3' ><div class='card-body homeQuizCard'>";
								$userInfoQuery = "SELECT * FROM tbaccounts WHERE user_id='".$key['user_id']."'";
								$userInfo = $mysqli->query($userInfoQuery);

								if($k = mysqli_fetch_array($userInfo)){
									$activityExists = FALSE;
									echo '<div class="profile-pic float-left mr-1" id="profileIcon" style="background-image: url(gallery/'.$k['user_image'].')" alt="..."></div>';

									$quizInfo = $mysqli->query("SELECT * FROM tbquizzes WHERE quiz_id='".$key['for_quiz']."'");
									
									if($q = mysqli_fetch_array($quizInfo)){
										$activityExists = TRUE;
										if (strcmp($key['user_id'], $_COOKIE["user"])==0) {
											echo '<h6 class="card-title mt-3" id="userName">You completed ';
										}
										else {
											echo '<h6 class="card-title mt-3" id="userName">'.strtok($k['email_address'],  '@').' completed ';
										}
										
										echo '<a class="quizLink" href="quizPage.php?quiz=';

										$whatToStrip = array("!",",",";"," ", "\"");
										$n = str_replace($whatToStrip, "-", $q['quiz_name']);
										echo $n.'">';
										
										echo $q['quiz_name'].'</a> with '.$key['score'].'/'.$key['out_of'].'</h6>';
									}

									if ($activityExists  != TRUE){
										if (strcmp($key['user_id'], $_COOKIE["user"])==0) {
											echo '<h6 class="card-title mt-3" id="userName">You completed ';
										}
										else {
											echo '<h6 class="card-title mt-3" id="userName">'.strtok($k['email_address'],  '@').' completed ';
										}

										echo 'a deleted quiz.';
									}
								}	

								echo '</div></div>';
							}
					}
				}
			?>
		</div>

		<div class="il">
			<?php 
				if (strcmp($unseen, 1) == 0) {
					echo '<a href="inbox.php"><div id="inboxLinkNotif"></div></a>';
				}
				else {
					echo '<a href="inbox.php"><div id="inboxLink"></div></a>';
				}
			?>
		</div>
		
		<script type="text/javascript" src="JS/logOut.js"></script>
		<script type="text/javascript" src="JS/searchBar.js"></script>

		<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

		<!--SEARCH HINT-->
			<script>
		  		const button = document.querySelector('#tooltipB');
		  		const tooltip = document.querySelector('#tt');

				  const popperInstance = Popper.createPopper(button, tooltip, {
				  	placement: 'bottom-start',

					  modifiers: [
					    {
					      name: 'offset',
					      options: {
					        offset: [0, -15],
					      },
					    },
					  ],
					});


				function showPopper() {
				 	document.getElementById('tt').style.opacity = "1"; 
				} 

				function hidePopper() {
				 	document.getElementById('tt').style.opacity = "0"; 
				} 
			</script>
	</body>
</html>