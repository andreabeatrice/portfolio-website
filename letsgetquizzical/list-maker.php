<?php 
	include 'conn.php';
	include 'logged-in.php';
	include 'redirect.php' ;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • list maker</title>

		<?php require 'head.php'; ?>
	</head>
	
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="listMaker-card">
				<div class="container mt-2"> <!--Class Header-->
					<h5 class="card-title" id="listMaker-title"> Your List: </h5>
					<hr/>
	    		</div> 
			

				<div class="card-body">
					<form method='POST' action="home.php" enctype='multipart/form-data' id="listForm" autocomplete="off">
						<div class='form-group'>
							<!--LIST NAME INPUT (REQUIRED)-->
								<div class="form-group row">
							   		<label for="listName" class="col-sm-2 col-form-label">List Name </label>
							   		<div class="col-sm-10">
							     		<input type="text" name="listName" class="form-control" id="listName" placeholder="The Listical List" required="required"/>
							   		</div>
								</div>

							<!--LIST DESCRIPTION INPUT (REQUIRED)-->
								<div class="form-group row">
							   		<label for="listDescription" class="col-sm-2 col-form-label">List Description </label>
							   		
							   		<div class="col-sm-10">
							     		<input type="text" name="listDescription" class="form-control" id="listDescription" placeholder="The Hardest List You'll Ever Make" required="required"/>
							   		</div>
								</div>

							<!--LIST TAGS INPUT (NOT REQUIRED)-->
								<div class="form-group row">
						   			<label for="listTags" class="col-sm-2 col-form-label">List Tags<br/> (Comma Separated) </label>
						   			<div class="col-sm-10">
						     			<input type="text" name="listTags" class="form-control" id="listTags" placeholder="Queer Themes, Why Did I Make This?, Romance" />
						   			</div>
								</div>

							<!--QUIZ INPUT (AUTOCOMPLETE USED SO THAT USER CAN ONLY ADD EXISTING QUIZZES TO LIST-->
								<div class="form-group row">
						   			<label for="addToList" class="col-sm-2 col-form-label">Search for a Quiz:</label>
						   			<div class="col-sm-10 autocomplete">
						     			<input type="text" name="addAList" class="form-control" id="addToList" placeholder="" maxlength="255" />
						   			</div>
								</div>
						
								<div class="form-group row">
									<ul id="quizList" ></ul>
								</div>

						</div>

						<input type="hidden" id="qList" name="qList" value="">

						<input type="submit" class='btn float-right'  name="submitList" value="SUBMIT"/>

						<small id='uploadHelp' class='text-danger'></small><br/>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="JS/autocomplete.js"></script>
		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>