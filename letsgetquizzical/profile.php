<?php
	if(FALSE)// toggle to false after debugging
	{
	  ini_set( 'display_errors', 'true');
	  error_reporting(-1);
	}
	
	include 'conn.php';

	include 'redirect.php' ;

	//FILE UPLOAD VARIABLES
		$outFile = ''; //output file error
		$outDesc = ''; //output quiz desc. error
		$uploadOk = 1; // for checking errors
		$outUp = ''; //output upload error
		$areFriends = 0;
		$pfp = '';

		if (!file_exists("gallery/")) {
			mkdir("gallery/", 0777, true);
		}

	//CHECK IF USER IS ADMIN
		include 'logged-in.php';
		$userInfo = li($mysqli)[0];
		$admin = false;

		if ($userInfo["admin"] == 1){
			$admin = true;
		}

	//CHECK IF PAGE IS PROFILE OF CURRENT USER & STORE VALUES TO DISPLAY
		$userI = $_GET['user'];

		if(strcmp($_COOKIE["user"], $userI) != 0)
			$profile = false;
		else
			$profile = true;


		$added = "SELECT * FROM tbaccounts WHERE user_id='".$userI."'";
		$array = mysqli_query($mysqli, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			$n = $val["first_name"];
			$s = $val["last_name"];
			$em = $val["email_address"];
			$d = $val["date_of_birth"];


			if ($profile || $admin) {
				$pass = $val["password"];
			}
		}




	//IF NOT USER PROFILE, DETERMINE RELATIONSHIP
	// 1 = FRIENDS
	// 2 = FRIEND REQUEST SENT
	// 3 = USER PROFILE
		if (!$profile){
			//ARE THEY FRIENDS WHERE FRIENDSHIP_ACCEPTED = 1
				$fri = "SELECT * FROM tbfriends WHERE friendship_creator='".$_COOKIE["user"]."' AND friendship_acceptor='".$userI."' AND friendship_accepted='1'";
				$ax = mysqli_query($mysqli, $fri);
				$fre = mysqli_fetch_all($ax, MYSQLI_ASSOC);

				foreach ($fre as $val) {
					$areFriends = 1;
				}

				$fri = "SELECT * FROM tbfriends WHERE friendship_acceptor='".$_COOKIE["user"]."' AND friendship_creator='".$userI."' AND friendship_accepted='1'";
				$ax = mysqli_query($mysqli, $fri);
				$fre = mysqli_fetch_all($ax, MYSQLI_ASSOC);

				foreach ($fre as $val) {
					$areFriends = 1;
				}

			//ARE THEY FRIENDS WHERE FRIENDSHIP_ACCEPTED = 2
				$fri = "SELECT * FROM tbfriends WHERE friendship_acceptor='".$_COOKIE["user"]."' AND friendship_creator='".$userI."' AND friendship_accepted='0'";
				$ax = mysqli_query($mysqli, $fri);
				$fre = mysqli_fetch_all($ax, MYSQLI_ASSOC);

				foreach ($fre as $val) {
					$areFriends = 2;
				}

				$fri = "SELECT * FROM tbfriends WHERE friendship_creator='".$_COOKIE['user']."' AND friendship_acceptor='".$userI."' AND friendship_accepted='0'";
				$ax = mysqli_query($mysqli, $fri);
				$fre = mysqli_fetch_all($ax, MYSQLI_ASSOC);

				foreach ($fre as $val) {
					$areFriends = 2;
				}
		}
		else {
			$areFriends = 3;
		}

	//IF USER HAS UPDATED PROFILE
		if (isset($_POST['upload'])) {
			//IF USER CHANGED THEIR NAME
				if (isset($_POST['newName'])){
					$nameArr = explode(" ", $_POST['newName']);
					$sql = "UPDATE tbaccounts SET first_name='".$nameArr[0]."', last_name='".$nameArr[1]."' WHERE user_id='".$userI."'";

					if ($mysqli->query($sql) === TRUE) {
						//header('Location: profilePage.php?user_id='.$user_id.'');
					} 
					else {
						echo "Error updating record: " . $mysqli->error;
					}
				}

			//IF USER CHANGED THEIR EMAIL ADDRESS
				if (isset($_POST['newEmail'])){
					$sql = "UPDATE tbaccounts SET email_address='".$_POST['newEmail']."' WHERE user_id='".$userI."'";

					if ($mysqli->query($sql) === TRUE) {
						//header('Location: profilePage.php?user_id='.$user_id.'');
					} 
					else {
						echo "Error updating record: " . $mysqli->error;
					}
				}

			//IF USER CHANGED THEIR DATE OF BIRTH
				if (isset($_POST['newDOB'])){
					$sql = "UPDATE tbaccounts SET date_of_birth='".$_POST['newDOB']."' WHERE user_id='".$userI."'";

					if ($mysqli->query($sql) === TRUE) {
						//header('Location: profilePage.php?user_id='.$user_id.'');
					} 
					else {
						echo "Error updating record: " . $mysqli->error;
					}
				}

			//IF USER CHANGED THEIR PASSWORD
				if (isset($_POST['newPassword'])){
					$sql = "UPDATE tbaccounts SET password='".$_POST['newPassword']."' WHERE user_id='".$userI."'";

					if ($mysqli->query($sql) === TRUE) {
						//header('Location: profilePage.php?user_id='.$user_id.'');
					} 
					else {
						echo "Error updating record: " . $mysqli->error;
					}
				}

			//IF USER DID NOT UPDATE THEIR PROFILE IMAGE
				if (empty($_FILES['uploadProfileIcon']['name'])){

				}
			
			//IF USER UPDATED THEIR PROFILE IMAGE	
				else{

					$target_dir = "gallery/"; //directory of files to placed 
					$uploadFile = $_FILES['uploadProfileIcon'];//file being uploaded
					$target_file = $target_dir . basename($uploadFile["name"]); // path of file to be uploaded
					$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);//holds file extnesion type
					$imageTypeAllowed = array('jpeg', 'jpg','png');
					$imageName = $uploadFile["name"];//file name and extension
					$fileNameTemp = $uploadFile["tmp_name"];//temp file name and extension

					move_uploaded_file($fileNameTemp,"gallery/" . $imageName);

					$sql = "UPDATE tbaccounts SET user_image='".$imageName."' WHERE email_address='".$em."'";

					if ($mysqli->query($sql) === TRUE) {
					
					} 
					else {
						echo "Error updating record: " . $mysqli->error;
					}
				}
		}

	//GET USER PROFILE IMAGE
		$added = "SELECT * FROM tbaccounts WHERE email_address='$em'";
		$array = mysqli_query($mysqli, $added);
		$row = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($row as $value) {
			$pfp = $value['user_image'];
		}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title><?php echo $n." ".$s;	?> • let's get quizzical</title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="card w-60 mt-3 mx-3 mb-3">
			<div class="card-body ">
				<form <?php echo 'action="profile.php?user='.$userI.'&view=quizzes"'; ?> id="profileForm" method="POST" enctype="multipart/form-data">
					<label for="uploadProfileIcon"><div class="profile-pic" id="overlay"></div>
					<img <?php echo 'src="gallery/'.$pfp.'" '; ?> class="img-fluid profile-pic"  id="profileIcon" alt="Responsive image"></label>
					<input type="file" id="uploadProfileIcon" name="uploadProfileIcon" class="hidden" disabled>

	    			<h5 class="card-title" id="userName"><?php echo $n." ".$s;	?></h5>

	    			<!--IF USER IS VIEWING THEIR OWN PROFILE, ALLOW THEM TO EDIT IT-->
			    		<?php 
			    			if ($profile || $admin)
			    				echo '<a href="#" class="btn float-right" onclick="enable()" id="editB">Edit Profile</a>';
			    		?>	    		
	    				<input type="submit" name="upload" class="hidden" id="submitButton" disabled />
	    			
	    			<!--DISPLAY BIRTHDAY MESSAGE-->
			    		<?php 
			    			$today = date("m-d");
			    			$d = substr($d, 5);
			    			if (strcmp($d, $today) == 0)
			    				echo '<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-birthday-cake"></i> Happy Birthday, '.$n.'!</h6>';
			    			else if($admin && $profile){
			    				echo '<br/><a href="admin-page.php" class="card-link"><h6 class="card-subtitle mb-2 mt-2 text-muted"><i class="fas fa-user-lock"></i> You are logged in as a site mod</h6></a>';
			    			}
			    			else if (strcmp($d, $today) == 0 && $profile && $admin) {
			    				echo '<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-birthday-cake"></i> Happy Birthday, '.$n.'! • <i class="fas fa-user-lock"></i> You are logged in as a site mod';
			    			}
			    		?>
	    			
	    			<hr/>

	    			<!--IF USER PROFILE || USERS ARE FRIENDS-->
	    				<!--HEADING-->
			    		<?php 
			    			if($areFriends != 0 || $profile || $admin){
			    				if ($profile)
			    					echo '<h5 class="card-subtitle mb-2 text-muted">YOUR INFORMATION:</h5>';
			    				else 
			    					echo '<h5 class="card-subtitle mb-2 text-muted">'.strtoupper($n).'\'S INFORMATION:</h5>';
				    		}
			    		?>
	    		
	    				<!--EMAIL/USERNAME-->
		    			<div class="form-group">
				    		<?php 
				    			if($areFriends != 0 || $profile  || $admin){
				    				if ($profile)
				    					echo '<label class="card-text" id="userEmail"><b>Email: </b><span>'.$em.'</span></label>';
				    				else 
				    					echo '<label class="card-text" id="userEmail"><b>Username: </b><span>'.strtok($em, '@').'</span></label>';
				    			}
				    		?>
		    			</div>
	    		
			    		<!--DATE OF BIRTH-->
			    		<div class="form-group">
			    			<?php 
			    			if($areFriends != 0 || $profile  || $admin)
			    				echo '<label class="card-text" id="userDOB"><b>Date-Of-Birth:</b> <span>'.$d.'</span></label>';
			    			?>
			    		</div>
	    		

	    				<div class="form-group">
			    			<?php 
			    			//IF PROFILE: DISPLAY PASSWORD SO USER CAN EDIT
				    			if($profile || $admin){
						    		echo '<label class="card-text" id="userPassword"><b>Password:</b> <span>';
						    		for ($i=0; $i < strlen($pass); $i++) { 
						    			echo "•";
						    		}
						    		echo '</span></label>';
					    		}
					    	//IF NOT PROFILE
					    			//IF FRIENDS, SHOW 'MESSAGE' AND 'UNFRIEND' BUTTONS
						    			if($areFriends == 1){
											echo '<p id="trueFriends">You and '.$n.' are friends</p>';
						    				echo '<a href="personalMessage.php?rid='.$_COOKIE['user'].'a'.$userI.'" class="card-link ml-4"><label class="card-text" id=""><b><i class="far fa-comments"></i> Message '.$n.'</b></label></a><br/>';
						    				echo '<a href="#" class="card-link ml-4"><label class="card-text" id=""><b><i class="fas fa-user-minus" onclick="unfriend(`'.strtok($em, '@').'`)"></i> Unfriend '.$n.'</b></label></a>';
										}
									//IF NOT FRIENDS, SHOW 'SEND REQUEST' BUTTON 
										else if($areFriends == 0) {
											echo '<a href="#" class="btn ml-3" onclick="sendFriendRequest(`'.strtok($em, '@').'`)"><i class="fas fa-user-plus mr-2"></i>Send '.$n.' a Friend Request</a>';
										}
									//IF REQUEST NOT ACCEPTED, SHOW APPROPRIATE MESSAGE
										else if($areFriends == 2){
											echo '<a href="#" class="btn btn-look text-muted ml-3" disabled="disabled">You\'ve Already Sent '.$n.' a Friend Request</a>';
										}
		    				?>
	    				</div>
	    			
	    			<hr/>

	    			<?php 
	    				//IF USERS ARE FRIENDS/USER IS VIEWING THEIR OWN PROFILE
		    				if($areFriends == 1 || $profile){
		    					echo '<ul class="nav nav-pills card-header-pills"><li class="nav-item"><a ';

		    					//HEADINGS
			    					//IF VIEW == QUIZZES
				    					if (strcmp($_GET['view'], "quizzes") == 0)
						        			echo ('class="nav-link-profile active"');
						        		else
						        			echo('class="nav-link-profile"');

						        		if ($profile)
						        			echo ' href="profile.php?user='.$_COOKIE['user'].'&view=quizzes">';
						        		else
						        			echo ' href="profile.php?user='.$userI.'&view=quizzes">';

					        			echo 'Quizzes</a></li>';

					        			echo '<li class="nav-item"><a ';

					        		//IF VIEW == LISTS
							        	if (strcmp($_GET['view'], "lists") == 0)
							        		echo ('class="nav-link-profile active" ');
							        	else
							        		echo('class="nav-link-profile" ');

							        	if ($profile)
							        		echo 'href="profile.php?user='.$_COOKIE['user'].'&view=lists">';
							        	else
							        		echo 'href="profile.php?user='.$userI.'&view=lists">';

							        	echo 'Lists</a></li>';

							        	echo '<li class="nav-item"><a ';

					        		//IF VIEW == COMPLETE
							        	if (strcmp($_GET['view'], "complete") == 0)
							        		echo ('class="nav-link-profile active" ');
							        	else
							        		echo('class="nav-link-profile" ');

							        	if ($profile)
							        		echo 'href="profile.php?user='.$_COOKIE['user'].'&view=complete">';
							        	else
							        		echo 'href="profile.php?user='.$userI.'&view=complete">';

							        	echo 'Completed Quizzes</a></li></ul> <hr/>';

				        		//CONTENT
							        //IF VIEW == QUIZZES
						        		if(strcmp($_GET['view'], "quizzes") == 0){
							    			if ($profile)
							    				echo '<h4 class="card-text">Your Quizzes:</h4>';
							    			else
							    				echo '<h4 class="card-text">'.$n.'\'s Quizzes:</h4>';
							    		
							    			echo '<div class="row">';

								    		$query = "SELECT * FROM tbaccounts WHERE email_address= '$em'";
											$res = $mysqli->query($query);
											if($row = mysqli_fetch_array($res)){

								    			$added = "SELECT * FROM tbquizzes WHERE user_id = '". $row['user_id']."'";
												$a = mysqli_query($mysqli, $added);
												$x = mysqli_fetch_all($a, MYSQLI_ASSOC);
												$numQ = 0;

												foreach (array_reverse($x) as $val) {
													$numQ++;
													echo '<div class="col-2">';
													echo '<div class="card" style="width: 18rem;">';

													echo '<a href="quizPage.php?quiz=';

													$whatToStrip = array("!",",",";"," ", "\"");
													$qs = str_replace($whatToStrip, "-", $val['quiz_name']);
													echo $qs;

													echo '" class="card-link">';

													$subquery = "SELECT * FROM tbgallery WHERE quiz_id = '".$val['quiz_id']."'";
													$quizArray = mysqli_query($mysqli, $subquery);
													$subrow = mysqli_fetch_all($quizArray, MYSQLI_ASSOC);

													foreach ($subrow as $value) {
														echo ' <img class="card-img-top" src="gallery/'.$value['image_name'].'" alt="Card image cap"/>';
													}

													echo '<div class="card-body"><h6 class="card-title">'.$val['quiz_name'].'</h6>';
													echo '<h6 class="card-subtitle mb-2 text-muted">'.$val['quiz_description'].'</h6></a>';
													
													//DISPLAY TAGS
														$tagArray = explode(',', $val['quiz_tags']);
														echo '<p class="card-text" >';
														foreach ($tagArray as $key) {
															echo '<a class="tag-link" href="tags.php?filter='.$key.'"><small class="text-muted" style="border-bottom: 1px dotted;">#'.$key.'</small></a>';
															echo '&ensp;';
														}

													echo '</p>';
													echo '</div></div></div>';
												}

												//IF USER HASN'T MADE ANY QUIZZES AND THEY'RE VIEWING THEIR OWN PROFILE
													if ($numQ == 0 && $profile){
														echo '<a href="quiz-maker.php" class="btn ml-3" >Add Quiz<span class="icon add-quiz"></span></a>';
													}
												//IF IT'S SOMEONE ELSE'S PROFILE AND THEY HAVEN'T MADE ANY QUIZZES
													else if ($numQ == 0 && !$profile){
														echo '<p>'.$n.' has no quizzes :(</p>';
													}
											}
											//REDUNDANCY TO CATCH ERRORS
												else {
													echo '<p> you have no quizzes :(</p>';
												}
										
											echo '</div>';
										}

									//IF VIEW == LISTS
										if (strcmp($_GET['view'], "lists") == 0){
											if ($profile)
							    				echo '<h4 class="card-text">Your Lists:</h4>';
							    			else
							    				echo '<h4 class="card-text">'.$n.'\'s Lists:</h4>';

							    			echo '<div class="row">';
				    		
								    		$myListsQuery = "SELECT * FROM tblists WHERE list_owner = '".$_COOKIE['user']."'";
											$lists = mysqli_query($mysqli, $myListsQuery);
											$listArray = mysqli_fetch_all($lists, MYSQLI_ASSOC);
											$numLists = 0;

											foreach (array_reverse($listArray) as $key) {
												$numLists++;
												echo '<div class="col-2">';
												echo '<a href="listPage.php?list=';

												$whatToStrip = array("!",",",";"," ", "\"");
												$list = str_replace($whatToStrip, "-", $key['list_name']);
												echo $list;

												echo '" class="card-link"><div class="card" style="width: 18rem;">';

												$subquery = "SELECT * FROM tbgallery WHERE quiz_id = '0'";
												$quizArray = mysqli_query($mysqli, $subquery);
												$subrow = mysqli_fetch_all($quizArray, MYSQLI_ASSOC);

												foreach ($subrow as $value) {
													echo ' <img class="card-img-top" src="gallery/'.$value['image_name'].'" alt="Card image cap"/>';
												}
												
												echo '<div class="card-body"><h6 class="card-title">'.$key['list_name'].'</h6>';
												echo '<h6 class="card-subtitle mb-2 text-muted">'.$key['list_description'].'</h6>';

												//DISPLAY LIST TAGS
													$tagArray = explode(',', $key['list_tags']);
													echo '<p class="card-text" >';
															
													foreach ($tagArray as $k) {
														echo '<small class="text-muted" style="border-bottom: 1px dotted;">#'.$k.'</small>';
														echo '&ensp;';
													}
												
												echo '</p>';
												echo '</div></div></a></div>';
											}
								
											//IF USER HASN'T MADE ANY LISTS AND THEY'RE VIEWING THEIR OWN PROFILE
												if ($numLists == 0 && $profile){
													echo '<a href="list-maker.php" class="btn ml-3" >Add List<span class="icon add-quiz"></span></a>';
												}
											//IF IT'S SOMEONE ELSE'S PROFILE AND THEY HAVEN'T MADE ANY LISTS
												else if ($numLists == 0 && !$profile) {
													echo '<p>'.$n.' has no lists :(</p>';
												}
											
											echo '</div>';
										}

									//IF VIEW == COMPLETE
										if (strcmp($_GET['view'], "complete") == 0){
							    			echo '<h4 class="card-text">Quizzes Marked as Complete:</h4>';

							    			echo '<div class="row">';

							    			$query = "SELECT * FROM tbaccounts WHERE email_address= '$em'";
											$res = $mysqli->query($query);

											if($row = mysqli_fetch_array($res)){
								    			$added = "SELECT * FROM tbactivities WHERE user_id = '". $row['user_id']."'";
												$a = mysqli_query($mysqli, $added);
												$x = mysqli_fetch_all($a, MYSQLI_ASSOC);
												$numAct = 0;

												foreach (array_reverse($x) as $val) {
													$exists = FALSE;
													$numAct++;
													$activitiesQuery = $mysqli->query("SELECT * FROM tbquizzes WHERE quiz_id= '".$val['for_quiz']."'");

													if($key = mysqli_fetch_array($activitiesQuery)){
														$exists = TRUE;
														echo '<div class="col-2">';
														echo '<div class="card" style="width: 18rem;"><a href="quizPage.php?quiz=';

														$whatToStrip = array("!",",",";"," ", "\"");
														$qs = str_replace($whatToStrip, "-", $key['quiz_name']);
														echo $qs;

														echo '" class="card-link">';
														$subquery = "SELECT * FROM tbgallery WHERE quiz_id = '".$val['for_quiz']."'";
														$quizArray = mysqli_query($mysqli, $subquery);
														$subrow = mysqli_fetch_all($quizArray, MYSQLI_ASSOC);

														foreach ($subrow as $value) {
															echo ' <img class="card-img-top" src="gallery/'.$value['image_name'].'" alt="Card image cap"/>';
														}

														echo '<div class="card-body"><h6 class="card-title">'.$key['quiz_name'].'</h6></a>';
														echo '<h6 class="card-subtitle mb-2 text-muted"> Score: '.$val['score'].' out of '.$val['out_of'].'</h6>';
														
														$tagArray = explode(',', $key['quiz_tags']);
														echo '<p class="card-text" >';

														//IF USER RATED QUIZ, SHOW RATING
															if ($val['user_rating'] != 0){
																if ($profile) 
																	echo 'Your rating: ';
																else
																	echo $n.'\'s rating: ';

																for ($i=0; $i < $val['user_rating']; $i++) { 
																	echo '<i class="fas fa-star"></i>';
																}
																echo '<br/>';
															}
														//DISPLAY QUIZ TAGS
															$tagArray = explode(',', $key['quiz_tags']);
															foreach ($tagArray as $k) {
																echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
																echo '&ensp;';
															}

														echo '</p></div></div></div>';
													}

													if(!$exists){
														echo '<div class="col-2">';
														echo '<div class="card" style="width: 18rem;">';


														echo '<div class="card-body"><h6 class="card-title">This quiz was deleted by the owner</h6></a>';
														echo '<h6 class="card-subtitle mb-2 text-muted"> Score: '.$val['score'].' out of '.$val['out_of'].'</h6>';
														echo '</div></div></div>';
													}
												}

												//IF USER HASN'T MADE DONE ANY QUIZZES AND THEY'RE VIEWING THEIR OWN PROFILE
													if ($numAct == 0 && $profile){
														echo '<p>You haven\'t done any quizzes yet.</p>';
													}
												//IF IT'S SOMEONE ELSE'S PROFILE AND THEY HAVEN'T DONE ANY QUIZZES
													else if ($numAct == 0 && !$profile){
															echo '<p>'.$n.' has no quizzes :(</p>';
													}

												echo '';
											}

											echo '</div>';
										}
		    				}

		    			//IF USERS ARE NOT FRIENDS - ONLY SHOW THE MOST RECENT QUIZ 
			    			else if ($areFriends == 0 || $areFriends == 2){
								echo '<h4 class="card-text">'.$n.'\'s Most Recent Quiz:</h4>';

					    		echo '<div class="row">';

					    		$query = "SELECT * FROM tbaccounts WHERE email_address= '$em'";
								$res = $mysqli->query($query);

								if($row = mysqli_fetch_array($res)){

						    		$added = "SELECT * FROM tbactivities WHERE user_id = '". $row['user_id']."' ORDER BY creation_time DESC LIMIT 1";
									$a = mysqli_query($mysqli, $added);
									$x = mysqli_fetch_all($a, MYSQLI_ASSOC);
									$numAct = 0;

										foreach (array_reverse($x) as $val) {
											$numAct++;
											$activitiesQuery = $mysqli->query("SELECT * FROM tbquizzes WHERE quiz_id= '".$val['for_quiz']."'");

											if($key = mysqli_fetch_array($activitiesQuery)){
												echo '<div class="col-2">';
												echo '<div class="card" style="width: 18rem;"><a href="quizPage.php?quiz=';

												$whatToStrip = array("!",",",";"," ", "\"");
												$qs = str_replace($whatToStrip, "-", $key['quiz_name']);
												echo $qs;

												echo '" class="card-link">';
												$subquery = "SELECT * FROM tbgallery WHERE quiz_id = '".$val['for_quiz']."'";
												$quizArray = mysqli_query($mysqli, $subquery);
												$subrow = mysqli_fetch_all($quizArray, MYSQLI_ASSOC);

												foreach ($subrow as $value) {
													echo ' <img class="card-img-top" src="gallery/'.$value['image_name'].'" alt="Card image cap"/>';
												}
												echo '<div class="card-body"><h6 class="card-title">'.$key['quiz_name'].'</h6></a>';
												echo '<h6 class="card-subtitle mb-2 text-muted"> Score: '.$val['score'].' out of '.$val['out_of'].'</h6>';
												$tagArray = explode(',', $key['quiz_tags']);
												echo '<p class="card-text" >';
												if ($val['user_rating'] != 0){
													if ($profile)
														echo 'Your rating: ';
													else 
														echo $n.'\'s Rating: ';

													for ($i=0; $i < $val['user_rating']; $i++) { 
														echo '<i class="fas fa-star"></i>';
													}
													echo '<br/>';
												}
												else {
													//echo 'You haven\'t rated this quiz yet.';
												}

												$tagArray = explode(',', $key['quiz_tags']);
												foreach ($tagArray as $k) {
													echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
													echo '&ensp;';
												}

												echo '</p></div></div></div>';
											}
										}

									//IF USER HASN'T DONE ANY QUIZZES
										if ($numAct == 0 ){
											echo '<p>'.$n.' has\'t done any quizzes :(</p>';
										}

									echo '';
								}

								echo '</div>';
			    			}
		    			
	    			?>

	    			<br/>



	    		</form>
			</div>

		</div>

		<script type="text/javascript" src="JS/logOut.js"></script>
		<script type="text/javascript" src="JS/drag-and-drop.js"></script>
		<script type="text/javascript">
			//FUNCTION TO ALLOW USER TO EDIT PROFILE INFORMATION
				enable = () => {
					var editName = document.createElement("I");
					editName.classList.add('fas', 'fa-pencil-alt', "userInfoB", "ml-1");
					editName.setAttribute('id','editNameButton');

					var editEmail = document.createElement("I");
					editEmail.classList.add('fas', 'fa-pencil-alt', "userInfoB", "ml-1");
					editEmail.setAttribute('id','editEmailButton');

					var editDOB = document.createElement("I");
					editDOB.classList.add('fas', 'fa-pencil-alt', "userInfoB", "ml-1");
					editDOB.setAttribute('id','editDOBButton');

					var editPassword = document.createElement("I");
					editPassword.classList.add('fas', 'fa-pencil-alt', "userInfoB", "ml-1");
					editPassword.setAttribute('id','editPasswordButton');

					document.getElementById('uploadProfileIcon').disabled = false;
					document.getElementById('submitButton').disabled = false;			
					document.getElementById('profileIcon').classList.add('edit_image');

					document.getElementById('profileIcon').classList.add('edit_image');

					document.getElementById('overlay').style.opacity = "0.6";

					document.getElementById('overlay').onmouseenter = function(){
						document.getElementById('overlay').style.cursor = "pointer";
					};

					document.getElementById('overlay').onmouseleave = function(){
						document.getElementById('overlay').style.cursor = "auto";

					};

					document.getElementById('userName').appendChild(editName); 
					document.getElementById('editNameButton').onclick = function(){
						//document.getElementById('overlay').style.cursor = "auto";
						$("#userName").html(
							$("<input></input>", {
								type: "text",
								name:"newName"
							}
						));

					};
					document.getElementById('userEmail').appendChild(editEmail);
					document.getElementById('editEmailButton').onclick = function(){
						//document.getElementById('overlay').style.cursor = "auto";
						$("#userEmail").find("span").html(
							$("<input></input>", {
								class: "ml-1",
								type: "email",
								name:"newEmail"
							})
						);

						$("#editEmailButton").removeClass("fas fa-pencil-alt");

					}; 
					document.getElementById('userDOB').appendChild(editDOB); 
					document.getElementById('editDOBButton').onclick = function(){
						//document.getElementById('overlay').style.cursor = "auto";
						$("#userDOB").find("span").html(
							$("<input></input>", {
								class: "ml-1",
								type: "date",
								name:"newDOB"
							})
						);

						$("#editDOBButton").removeClass("fas fa-pencil-alt");

					}; 
					document.getElementById('userPassword').appendChild(editPassword);
					document.getElementById('editPasswordButton').onclick = function(){
						//document.getElementById('overlay').style.cursor = "auto";
						$("#userPassword").find("span").html(
							$("<input></input>", {
								class: "ml-1",
								type: "password",
								name:"newPassword"
							})
						);

						$("#editPasswordButton").removeClass("fas fa-pencil-alt");

					};
					document.getElementById('editB').innerHTML = "<label id='changeToSub' for='submitButton'>Done</label>";	
					document.getElementById('editB').onclick = '';			
				}

			//FUNCTION THAT ALLOWS USER TO SEND FRIEND REQUEST IF THEY'RE VIEWING SOMEONE ELSE'S PROFILE (USES AJAX)
				sendFriendRequest = (elem) => {
					$.ajax({
				       url : 'friendFunctionality.php', //PHP file to execute
				       type : 'POST', //method used POST or GET
				       data : {
				        requestType : "friendRequest",
				        friendship_acceptor: elem

				        }, // Parameters passed to the PHP file
				       success : function(result){ // Has to be there !
				           //autocomplete(document.getElementById("addToList"), foundArray);
				           //console.log(result);
				           //change button
				       },

				       error : function(result, statut, error){ // Handle errors

				       }

				    });
				}

			//RELOAD PAGE AFTER AJAX REQ
				$(document).ajaxStop(function(){
				    window.location.reload();
				});

			//UNFRIEND FUNCTION
			unfriend = (elem) =>{
				$.ajax({
				    url : 'deleteFunctions.php', //PHP file to execute
				    type : 'POST', //method used POST or GET
				    data : {
				       	type: "deleteFriendship",
				       	unfriend: elem
				    }, // Parameters passed to the PHP file
				    success : function(result){ // Has to be there !
				           //autocomplete(document.getElementById("addToList"), foundArray);
				        console.log(result);
				           //change button
				    },

				       error : function(result, statut, error){ // Handle errors
				       	console.log(result);
				       }

				    });
			}
		</script>
	</body>
</html>