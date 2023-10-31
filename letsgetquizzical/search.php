<?php
	include 'logged-in.php' ;
	include 'conn.php' ;
	// Get search term 
	$searchTerm = $_GET['search']; 
	 
	$added = "SELECT * FROM tbquizzes WHERE quiz_name LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$quizData = mysqli_fetch_all($a, MYSQLI_ASSOC);

	$added = "SELECT * FROM tbquizzes WHERE quiz_tags LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$z = mysqli_fetch_all($a, MYSQLI_ASSOC); 

	foreach ($z as $val) {
		array_push($quizData, $val);
	}

	$added = "SELECT * FROM tblists WHERE list_name LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$listData = mysqli_fetch_all($a, MYSQLI_ASSOC);

	$added = "SELECT * FROM tblists WHERE list_tags LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$z = mysqli_fetch_all($a, MYSQLI_ASSOC); 

	foreach ($z as $val) {
		array_push($listData, $val);
	}


	$added = "SELECT * FROM tbaccounts WHERE email_address LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$accountData = mysqli_fetch_all($a, MYSQLI_ASSOC); 

	$added = "SELECT * FROM tbaccounts WHERE first_name LIKE '%".$searchTerm."%'";
	$a = mysqli_query($mysqli, $added);
	$z = mysqli_fetch_all($a, MYSQLI_ASSOC); 

	foreach ($z as $val) {
		array_push($accountData, $val);
	}

	$accountData = array_map("unserialize", array_unique(array_map("serialize", $accountData)));
	$quizData = array_map("unserialize", array_unique(array_map("serialize", $quizData)));
	$listData = array_map("unserialize", array_unique(array_map("serialize", $listData)));


	//echo json_encode($quizData); 
	$n = str_replace("%20", " ", $_GET['search']);


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
						<h5 class="card-title" id="quiztitle">Search results matching: <?php echo $n; ?></h5>

						<hr/>
						
			    	</div>
			    	<div class="card-body">
			    		<?php 
			    			//DISPLAY ALL USERS FOUND
			    				if (count($accountData) != 0) {
									echo '<h5 class="card-subtitle">Users</h5>';
									foreach ($accountData as $key) {
										echo "<a href='profile.php?user=".$key["user_id"]."&view=quizzes' class='card-link'><li class='list-group-item'>";

										echo "<img src='gallery/".$key["user_image"]."' width='5%' style='border-radius:100%;'/>";
										echo "<p class='card-text d-inline ml-3'>".strtok($key['email_address'],  '@')."</p>";
										echo "</li></a>";
									}
									echo '<br/>';
								}
							//DISPLAY ALL QUIZZES FOUND
								if (count($quizData) != 0) {
									echo '<h5 class="card-subtitle">Quizzes</h5>';
									foreach ($quizData as $key) {
										$whatToStrip = array("!",",",";"," ", "\"");
										$name = str_replace($whatToStrip, "-", $key['quiz_name']);

										echo "<a href='quizPage.php?quiz=".$name."' class='card-link'><li class='list-group-item'>";

										$quizImageQuery = "SELECT * FROM tbgallery WHERE quiz_id='".$key['quiz_id']."'";
										$ImgArray = $mysqli->query($quizImageQuery);

										if($image = mysqli_fetch_array($ImgArray)){
											echo "<img src='gallery/".$image['image_name']."' width='8%' alt='...'>";
										}
										
										echo "<p class='card-text d-inline ml-3'>".$key['quiz_name']." <br/>";

										$tagArray = explode(',', $key['quiz_tags']);
										foreach ($tagArray as $k) {
											echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
											echo '&ensp;';
										}

										$qOwner = "SELECT * FROM tbaccounts WHERE user_id='".$key['user_id']."'";
										$oArr = $mysqli->query($qOwner);

										if($person = mysqli_fetch_array($oArr)){
											echo "<small class='text-muted float-right'><a href='profile.php?user=".$person['user_id']."&view=quizzes' class='card-link'><span class='icon profile mr-1'></span>".strtok($person['email_address'],  '@')."</a></small>";
										}


										echo "</p>";
										echo "</li></a>";
									}
									echo '<br/>';
								}
							//DISPLAY ALL LISTS FOUND
								if (count($listData) != 0) {
									echo '<h5 class="card-subtitle">Lists</h5>';
									foreach ($listData as $key) {
										$whatToStrip = array("!",",",";"," ", "\"");
										$name = str_replace($whatToStrip, "-", $key['list_name']);

										echo "<a href='listPage.php?list=".$name."' class='card-link'><li class='list-group-item list-g'>";

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
										echo '<div class="lCover">';
											foreach ($imArr as $k) {
												if($i < 4){
													echo "<img src='gallery/".$k."' width='4%' alt='...'>";
													if ($i % 2 != 0){
														echo '<br/>';
													}
													$i++;
												}
											}

											if($i < 4){
												
												foreach (array_reverse($imArr) as $k) {
													if($i < 4){
														echo "<img src='gallery/".$k."' width='4%' alt='...'>";
														if ($i % 2 != 0 && $i!=3){
															echo '<br/>';
														}
														$i++;
													}
												}
											}
										echo '</div>';
										
										echo "<div class='l-info'>";
											echo "<p class='card-text d-inline ml-3'>".$key['list_name']." <br/>";

											$tagArray = explode(',', $key['list_tags']);
											foreach ($tagArray as $k) {
												echo '<a class="tag-link" href="tags.php?filter='.$k.'"><small class="text-muted" style="border-bottom: 1px dotted;">'.$k.'</small></a>';
												echo '&ensp;';
											}

											$qOwner = "SELECT * FROM tbaccounts WHERE user_id='".$key['list_owner']."'";
											$oArr = $mysqli->query($qOwner);

											if($person = mysqli_fetch_array($oArr)){
												echo "<small class='text-muted float-right'><a href='profile.php?user=".$person['user_id']."&view=quizzes' class='card-link'><span class='icon profile mr-1'></span>".strtok($person['email_address'],  '@')."</a></small>";
											}


											echo "</p>";
											echo '</div>';
										echo "</li></a>";
									}
									echo '<br/>';
								}
						?>
			    	</div>
				</div>



			
		</div>

		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>