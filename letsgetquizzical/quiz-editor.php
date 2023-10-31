<?php

	include 'conn.php';
	include 'logged-in.php';
	include 'redirect.php' ;

	$n = str_replace("-", " ", $_GET['quiz']);

	$userInfo = li($mysqli)[0];

	$myQuiz = false;

	$quizImage;
	$tagArray = array();
	$quizInfo = array();

	$quiz = "SELECT * FROM tbquizzes WHERE quiz_name='".$n."'";
	$array = mysqli_query($mysqli, $quiz);
	$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

	foreach ($x as $val) {
		$quizInfo = $val;
		if (strcmp($val['user_id'], $userInfo["uId"]) == 0) {
			// code...
			$myQuiz = true;
		}

		$arr = mysqli_query($mysqli, "SELECT * FROM tbgallery WHERE quiz_id='".$val['quiz_id']."'");
		$y = mysqli_fetch_all($arr, MYSQLI_ASSOC);

		foreach ($y as $key) {
			$quizImage = $key['image_name'];
		}

		$tagArray = explode(',', $val['quiz_tags']);

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
			<form action='home.php' method='POST' enctype='multipart/form-data' id="quizEditorForm">
				<div class="card mt-3" id="quizMaker-card">
					<!--QUIZ HEADER-->
						<div class="container mt-2"> 
							<div id="dynImgDiv">
								<label for="uploadNewQuizIcon"><img <?php echo 'src="gallery/'.$quizImage.'" '; ?> class="img-fluid" id="dnd" alt="Responsive image"></label>
								<input type="file" id="uploadNewQuizIcon" name="uploadNewQuizIcon" class="hidden">
							</div>
							<h5 class="card-title" id="quiztitle"><span id="quizName"></span> <i class="fas fa-pencil-alt"></i></h5>
							<br/>
							<h6 class="card-subtitle mt-1 mb-1 text-muted" id="quizSub"><span id="quizDescription"></span> <i class="fas fa-pencil-alt" ></i></h6>
							<h6>
								<?php 
									echo "<span class='icon-input-btn'><span class='icon check'></span> <input type='submit' class='btn btn-primary mr-2' value='Finished Editing' name='editQuiz' id='subBut' /></span> ";
									echo '<a href="#" class="btn " id="delB" onclick="deleteThis(`'.$quizInfo['quiz_id'].'`)"><i class="fas fa-trash"></i> Delete Quiz</a>';
								?> 							
							</h6>
							<br/>
							<h6 class="card-subtitle mb-1 text-muted"> Tags: <span id="quizTags">
								<?php 
								//TAGS
									foreach ($tagArray as $k) {
										echo '<a class="tag-link" href="tags.php?filter='.$k.'" style="border-bottom: 1px dotted;">'.$k.'</a>';
										echo '&ensp;';
									}
								?></span><i class="fas fa-pencil-alt"></i>
							</h6>
							<hr/>
			    		</div> 
				
					<div class="card-body">
						<ul class="list-group list-group-flush"></ul>

						<input type="hidden" name="quiz_id" <?php echo 'value="'.$quizInfo['quiz_id'].'"'; ?> />
					</div>
				</div>
			</form>
		</div>

		<script type="text/javascript" src="JS/editQuiz.js"></script>
		<script type="text/javascript" src="JS/drag-and-drop.js"></script>
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
		</script>
	</body>
</html>