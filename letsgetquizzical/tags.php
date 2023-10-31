<?php 
	include 'conn.php';
	include 'redirect.php' ;

	$f = $_GET['filter'];
	$n = str_replace("%20", " ", $_GET['filter']);
	$NUM = 0;

	$quizListQuery = "SELECT * FROM tbquizzes";
	$answer = mysqli_query($mysqli, $quizListQuery);
	$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);

	foreach (array_reverse($returned) as $key) {
		if (strpos(strtoupper($key['quiz_tags']), strtoupper($f)) !== false){
			$NUM++;
		}
	}

	//HELPER FUNCTION - LAST UPDATED
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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • <?php echo $n; ?></title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<!--PAGE HEADER-->
				<div class="card mt-3" id="quizMaker-card">
					<div class="container mt-2">
						<h5 class="card-title" id="quiztitle"><?php echo $NUM; ?> Quizzes in 
							<a class="big-tag" href=<?php echo '"tags.php?filter='.$f.'"';  ?>><span ><?php echo $n ?></span></a>
						</h5>

						<hr/>
			    	</div>
				</div>

			<!--DISPLAY QUIZZES WHERE QUIZ_TAGS INCLUDES 'FILTER'-->
				<?php
					$quizListQuery = "SELECT * FROM tbquizzes";
					$answer = mysqli_query($mysqli, $quizListQuery);
					$returned = mysqli_fetch_all($answer, MYSQLI_ASSOC);

					foreach (array_reverse($returned) as $key) {
						if (strpos(strtoupper($key['quiz_tags']), strtoupper($f)) !== false){

							echo "<div class='card my-3' ><div class='card-body homeQuizCard'>";
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
								echo "</div> <div class='card-footer bg-transparent border-transparent'><p style='text-align: right;'class='card-text float-right'><small class='text-muted'><span class='icon profile mr-1'></span>".strtok($owner['email_address'],  '@')."<br/> <i class='fas fa-history mr-1'></i>";
										
								lastUpdated(new DateTime($key['creation_time']));

								echo "</small></p></div>";
							}
								
							echo '</div>';
						}			
					}
				?>
		</div>

		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>