<?php
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$outFile = ''; //output file error
	$outDesc = ''; //output quiz desc. error
	$uploadOk = 1; // for checking errors
	$outUp = ''; //output upload error
	$target_dir = "gallery/";

	include('logged-in.php');

	if (isset($_POST['upload'])) {
		$tmp_name = $_FILES["picToUpload"]["tmp_name"];
		$target_file = $target_dir . basename($_FILES["picToUpload"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		if ($_FILES["picToUpload"]["size"] >= 1000000) {
			$uploadOk = 0;
		}
		else {
					//if the file is <1MB, check that it's a JPG
			if($imageFileType != "jpg" && $imageFileType != "jpeg") {
				$outFile = "Sorry, only JPG/JPEG files are allowed.";
				$uploadOk = 0;
			}
			else {
				$outFile = "";
				//everything is valid :)
			}
		}

		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		if (move_uploaded_file($_FILES["picToUpload"]["tmp_name"], $target_file)) {
			$added = "UPDATE tbaccounts SET user_image=".basename( $_FILES["picToUpload"]["name"])." WHERE email='".$email."' AND password='".$pass."'";
			mysqli_query($mysqli, $added);
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>let's get quizzical</title>
	<meta name="author" content="Andrea Blignaut">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<link rel="icon" href="media/icon.ico" type="image/icon type">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body id="home_body">
	<div id="head">
		<?php include 'header.php'; ?>
	</div>

	<div class="card w-60 mt-3 mx-3">
		<div class="card-body ">
			<form action="profile.php" id="profileForm" method="POST">
				<input type="file" id="picToUpload" name="picToUpload" class="hidden" disabled>

	    		<label for="picToUpload">
	    			<div class="profile-pic" id="overlay"></div>
	    			<div class="profile-pic" id="profileIcon"></div>
	    			
	    		</label> 

	    		<h5 class="card-title"> 
	    			
	    				<?php echo $name; ?> <?php echo $surname; ?>
	    		</h5>

	    		<?php 
	    			$today = date("m-d");
	    			$date = substr($date, 5);
	    			if (strcmp($date, $today)==0)
	    				echo '<br/><h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-birthday-cake"></i> Happy Birthday, '.$name.'!</h6>';
	    		 ?>
	    		<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
	    		<!-- <a href="#" class="card-link">Card link</a> -->
	    		<a href="#" class="card-link float-right" onclick="enable()" id="editB"><label for="submitButton">Edit Profile</label></a>
	    		<a href="#" class="card-link float-right" id="editB"><label for="submitButton">XXX</label></a>
	    		<input type="submit" name="upload" class="hidden" id="submitButton" disabled />
	    	</form>
  		</div>
	</div>

	<script type="text/javascript">
		function enable() {
			document.getElementById('picToUpload').disabled = false;
			document.getElementById('submitButton').disabled = false;			
			document.getElementById('profileIcon').classList.add('edit_image');

			document.getElementById('profileIcon').classList.add('edit_image');

			document.getElementById('overlay').onmouseenter = function(){
				document.getElementById('overlay').style.animation = "fade 0.5s ease-in forwards";
			};

			document.getElementById('overlay').onmouseleave = function(){
				document.getElementById('overlay').style.animation = "fadeout 0.5s ease-in forwards";

			};

			document.getElementById('editB').innerHTML = "Done";			
		}
	</script>
</body>
</html>