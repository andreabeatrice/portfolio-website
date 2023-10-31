<?php

	include 'conn.php';
	include 'logged-in.php';
	include 'redirect.php' ;

	$n = str_replace("-", " ", $_GET['list']);

	$userInfo = li($mysqli)[0];

	$myQuiz = false;

	$quizImage;
	$tagArray = array();
	$quizInfo = array();

	$list = "SELECT * FROM tblists WHERE list_name='".$n."'";
	$array = mysqli_query($mysqli, $list);
	$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

	foreach ($x as $val) {
		$quizInfo = $val;
		if (strcmp($val['list_owner'], $_COOKIE["user"]) == 0) {
			// code...
			$myQuiz = true;
		}

		$tagArray = explode(',', $val['list_tags']);

	}
	$listId = $val['list_id'];
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
					<form <?php echo 'action="listPage.php?list='.$_GET['list'].'"' ?> method='POST' enctype='multipart/form-data' id="quizEditorForm">
					<h5 class="card-title " id="quiztitle">
						<input type="text" class="form-control" name="lName"  <?php echo 'value="'.$n.'"'.' placeholder="'.$n.'"'; ?> /> 
					</h5>
					<h6 class="card-subtitle mb-1 text-muted" id="quizSub">
						<input type="text" class="form-control" name="lDesc"  <?php echo 'value="'.$listInfo['list_description'].'"'.' placeholder="'.$listInfo['list_description'].'"'; ?> />
					</h6>

							<h6>
								<?php 
									echo "<span class='icon-input-btn'><span class='icon check'></span> <input type='submit' class='btn btn-primary float-right mr-2' value='Finished Editing' name='editList' id='subBut' /></span> ";
									echo '<input type="hidden" id="listId" name="listId" value="'.$listId.'">';
								?>
							</h6>
		    		</form>
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