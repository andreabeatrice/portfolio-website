<?php 
	include 'conn.php';
	include 'logged-in.php';
	include 'redirect.php' ;

	$n = str_replace("-", " ", $_GET['quiz']);
	$n = str_replace("%27", "", $n);

	$userInfo = li($mysqli)[0];

	$quizImage;

	$quizId;


	//CHECK IF QUIZ IS OWNED BY CURRENT USER
		$myQuiz = false;

		$quiz = "SELECT * FROM tbquizzes WHERE quiz_name='".$n."'";
		$array = mysqli_query($mysqli, $quiz);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			if (strcmp($val['user_id'], $userInfo["uId"]) == 0) {
				// code...
				$myQuiz = true;
				echo "<script>
				let qId = '".$val['quiz_id']."';
				let uId = '".$_COOKIE['user']."';
				let retVal = true;</script>";
			}

			$quizId = $val['quiz_id'];

			$arr = mysqli_query($mysqli, "SELECT * FROM tbgallery WHERE quiz_id='".$val['quiz_id']."'");
			$y = mysqli_fetch_all($arr, MYSQLI_ASSOC);

			foreach ($y as $key) {
				$quizImage = $key['image_name'];

			}
		}

	//CHECK IF USER IS AN ADMIN
		$admin = false;

		if ($userInfo["admin"] == 1){
			$admin = true;
		}

	//CHECK IF USER HAS DONE QUIZ BEFORE

		if (!$myQuiz && !$admin){
			$act = "SELECT * FROM tbactivities WHERE user_id='".$_COOKIE['user']."' AND for_quiz='".$quizId."'";
			$doneBefore = mysqli_query($mysqli, $act);
			$dbArray = mysqli_fetch_all($doneBefore, MYSQLI_ASSOC);

			foreach ($dbArray as $val) {
				echo "<script>
					let qId = '".$quizId."';
					let uId = '".$_COOKIE['user']."';
					let retVal = confirm(`You've done this quiz before. If you proceed, you will lose your previous score.`);
					</script>";
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
	<body id="body" onload="getConfirmation()">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="quizMaker-card">
				<!--QUIZ HEADER-->
					<div class="container mt-2">
						<div id="quizImgOnPage">
							<img <?php echo 'src="gallery/'.$quizImage.'" '; ?> class="img-fluid" id="img" alt="Responsive image">
						</div>
						<br/>

						<!--TITLE (TO BE ADDED BY DISPLAYQUIZ.JS)-->
							<h5 class="card-title" id="quiztitle"></h5>

						<!--DESCRIPTION (TO BE ADDED BY DISPLAYQUIZ.JS)-->
							<h6 class="card-subtitle mb-1 text-muted" id="quizSub"></h6>

						<!--IF QUIZ IS OWNED BY CURRENT USER SHOW EDIT & DELETE BUTTONS-->
							<h6>
								<?php 
									if ($admin && !$myQuiz){
										echo '<h5 class="card-subtitle mb-2 mt-2 text-muted d-inline"><i class="fas fa-user-lock"></i> Site mod: </h5>';
									}
									if ($myQuiz || $admin) {
										echo '<a href="quiz-editor.php?quiz='.$_GET['quiz'].'" class="btn mr-3" id="editB"><i class="far fa-edit"></i> Edit Quiz </a>';
										echo '<a href="#" class="btn " id="delB" onclick="deleteThis(`'.$quizId.'`)"><i class="fas fa-trash"></i> Delete Quiz</a>';
									}
								?>
							</h6>
						
			    	</div>
				
			    <!--WHERE DISPLAYQUIZ.JS WILL ATTACH QUESTIONS-->
					<div class="card-body">
						<ul class="list-group list-group-flush">
						</ul>
					</div>
			</div>
		</div>

		<script type="text/javascript" src="JS/displayQuiz.js"></script>
		<script type="text/javascript" src="JS/logOut.js"></script>
		<script type="text/javascript">
				deleteThis = (qId) => {
					$.ajax({
				       url : 'deleteFunctions.php', //PHP file to execute
				       type : 'POST', //method used POST or GET
				       data : {
				        type: "deleteQuiz",
				        for_quiz: qId
				    }, // Parameters passed to the PHP file
				       success : function(result){ // Has to be there !
				       	console.log(result);
				       },

				       error : function(result, statut, error){ // Handle errors

				       }

				    });

				    window.location.href = "home.php"; 

				}
				
				getConfirmation = () => {
	               if( retVal == true ) {
	               	$.ajax({
				       url : 'deleteFunctions.php', //PHP file to execute
				       type : 'POST', //method used POST or GET
				       data : {
				        type: "deleteActivity",
				        for_quiz: qId,
				        from_user: uId			        
				    }, // Parameters passed to the PHP file
				       success : function(result){ // Has to be there !
				       },

				       error : function(result, statut, error){ // Handle errors

				       }

				    });

	               } else {
	                	window.location.href = "home.php";
	               }
	            }
			
		</script>
	</body>
</html>