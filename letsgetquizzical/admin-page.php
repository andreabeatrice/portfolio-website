<?php 
	include 'conn.php';

	include 'redirect.php' ;

	$success = false;

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

	if (isset($_POST['makeChange'])) {

		if (isset($_POST['newCat']) && strcmp($_POST['newCat'], "") != 0) {
			$c = $_POST['newCat'];

			$query = "INSERT INTO tbcategories (category) VALUES ('$c');";

			$res = mysqli_query($mysqli, $query) == TRUE;

			if($res){
				//
			}
		}

		if (isset($_POST['dc']) && strcmp($_POST['dc'], "") != 0) {
			$c = $_POST['dc'];

			$query = "DELETE FROM tbcategories WHERE category='$c';";

			$res = mysqli_query($mysqli, $query) == TRUE;

			if($res){
				//
			}
		}

		if (isset($_POST['adminList']) && strcmp($_POST['adminList'], "") != 0) {
			$users = explode(',', $_POST['adminList']);

			foreach ($users as $email){
				$query = "UPDATE tbaccounts SET admin = '1' WHERE email_address LIKE '%".$email."%'";

				$res = mysqli_query($mysqli, $query) == TRUE;

				if($res){
					$success = true;
				}
			}
			
		}

		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • administrator</title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="listMaker-card">
				<div class="container mt-2"> <!--Class Header-->
					<h5 class="card-title" id="listMaker-title"> Admin Capabilities </h5>
					<hr/>
	    		</div> 
			

				<div class="card-body">
					<form action='admin-page.php' method='POST' enctype='multipart/form-data' id="quizForm">
						<div class='form-group'>
							<!--NEW CATEGORY -->
								<div class="form-group row">
							   		<label for="newCat" class="col-sm-2 col-form-label">Add a category: </label>
							   		
							   		<div class="col-sm-10">
							     		<input type="text" name="newCat" class="form-control" id="newCat" placeholder="Quizzes"/>
							   		</div>
								</div>

							<!--DROP CATEGORY-->
								<div class="form-group row">
						   			<label for="quizCategory" class="col-sm-2 col-form-label">Drop Category </label>
						   			
						   			<div class="col-sm-10">
						     			<select type="text" name="dc" class="form-control" id="quizCategory"  placeholder="Quiz Catergory" >
								     	  <option value="" selected disabled hidden>Quiz Category</option>
								     	  <?php
											$cat = mysqli_query($mysqli, "SELECT * FROM tbcategories");
											$categories = mysqli_fetch_all($cat, MYSQLI_ASSOC);

											foreach ($categories as $value) {
												echo '<option value="'.$value['category'].'">'.$value['category'].'</option>';
											}

								     	  ?>

						     			</select>
						   			</div>
								</div>

							<!--NEW ADMIN -->
								<div class="form-group row">
							   		<label for="addAdmin" class="col-sm-2 col-form-label">New Admin: </label>
							   		
							   		<div class="col-sm-10 autocomplete">
								    	<input type="text" name="addAList" class="form-control" id="addToList" placeholder="" maxlength="255" />
								    	<input type="hidden" id="adminList" name="adminList" value="">
									</div>
								</div>

								<div class="form-group row">
									<ul id="quizList" ></ul>
								</div>
						</div>

						<input type='submit' class='btn float-right' value='Done' name='makeChange' id="subBut" />
					</form>
				</div>

			</div>
		</div>


		<!--JAVASCRIPT FOR AUTOCOMPLETEING USERNAMES IN SEARCH-->
		<script type="text/javascript" src="JS/autocompleteUsers.js"></script>
		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>