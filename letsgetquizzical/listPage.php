<?php 
	include 'conn.php';
	$n = str_replace("-", " ", $_GET['list']);

	include 'redirect.php' ;
	include 'logged-in.php';
	$userInfo = li($mysqli)[0];

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

	//CHECK IF LIST IS OWNED BY CURRENT USER
		$myList = false;
		$listInfo = array();

		$lst = "SELECT * FROM tblists WHERE list_name='".$n."'";
		$array = mysqli_query($mysqli, $lst);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			if (strcmp($val['list_owner'], $_COOKIE["user"]) == 0) {
				// code...
				$myList = true;
			}

			$listInfo = $val;
		}

	//CHECK IF USER IS AN ADMIN
		$admin = false;

		if ($userInfo["admin"] == 1){
			$admin = true;
		}

	//IF QUIZ WAS EDITED
		if (isset($_POST['editList'])) {
			$listN = $_POST['lName'];
			$listD = $_POST['lDesc'];

			$listN = str_replace( "\"", "”", $listN);
			$listN = str_replace( "'", "’", $listN);
			$listD = str_replace( "\"", "”", $listD);
			$listD = str_replace( "'", "’", $listD);

			$date = date("Y-m-d H:i:s"); 
			$fixList = "UPDATE tblists SET list_name='".$listN."', list_description='".$listD."', creation_time='".$date."' WHERE list_id='".$_POST['listId']."'";

				if (mysqli_query($mysqli, $fixList)){
					$listFixed = true;
					$listN = str_replace( " ", "-", $listN);
					header("Location: listPage.php?list=".$listN);
				}
				else {
					echo mysqli_error($mysqli);
				}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • <?php echo $n ?></title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="quizMaker-card">
				<div class="container mt-2">
					<h5 class="card-title" id="quiztitle"><?php echo $n; ?></h5>
					<h6 class="card-subtitle mb-1 text-muted" id="quizSub"><?php echo $listInfo['list_description']; ?></h6>

					<!--IF QUIZ IS OWNED BY CURRENT USER SHOW EDIT & DELETE BUTTONS-->
							<h6>
								<?php 
									if ($admin && !$myList){
										echo '<h5 class="card-subtitle mb-2 mt-2 text-muted d-inline"><i class="fas fa-user-lock"></i> Site mod: </h5>';
									}
									if ($myList || $admin) {
										echo '<a href="list-editor.php?list='.$_GET['list'].'" class="btn mr-3 mb-2" id="editB"><i class="far fa-edit"></i> Edit List </a>';
										/*echo '<a href="#" class="btn mb-2" id="delB"><i class="fas fa-trash"></i> Delete List</a>';*/
									}
								?>
							</h6>
		    	</div>
		    </div>

				<div class="container mt-3" id="homeQuizList">
					<?php 
						$quizListQuery = "SELECT * FROM tblists WHERE list_name='".$n."'";
						$answer = mysqli_query($mysqli, $quizListQuery);

						$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);

						foreach ($returned as $key) {
							$nArray = explode(",", $key["list_quizzes"]);
						}


						foreach ($nArray as $k) {
							$ql = "SELECT * FROM tbquizzes WHERE quiz_name='".$k."'";
							$answer = mysqli_query($mysqli, $ql);

							$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);

							foreach (array_reverse($returned) as $key) {
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
						}

					?>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>