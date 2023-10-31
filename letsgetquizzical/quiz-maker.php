<?php 
	include 'conn.php';
	include 'redirect.php' ;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • quiz maker</title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="quizMaker-card">
				 <!--PAGE HEADING-->
				<div class="container mt-2">
					<h5 class="card-title" id="quizMaker-title"> Let's Make A Quiz:</h5>
					<hr/>
	    		</div>

				<div class="card-body">
					<form action='home.php' method='POST' enctype='multipart/form-data' id="quizForm">
						<div class='form-group'>
							<!--QUIZ NAME - REQUIRED -->
								<div class="form-group row">
							   		<label for="quizName" class="col-sm-2 col-form-label">Quiz Name </label>
							   		
							   		<div class="col-sm-10">
							     		<input type="text" name="quizName" class="form-control" id="quizName" placeholder="The Quizzical Quiz" required="required"/>
							   		</div>
								</div>

							<!--QUIZ DESCRIPTION - REQUIRED-->
								<div class="form-group row">
							   		<label for="quizDescription" class="col-sm-2 col-form-label">Quiz Description </label>
							   		<div class="col-sm-10">
							     		<input type="text" name="quizDescription" class="form-control" id="quizDescription" placeholder="The Hardest Quiz You'll Ever Do" required="required"/>
							   		</div>
								</div>

							<!--QUIZ CATEGORY - REQUIRED-->
								<div class="form-group row">
						   			<label for="quizCategory" class="col-sm-2 col-form-label">Quiz Category </label>
						   			
						   			<div class="col-sm-10">
						     			<select type="text" name="quizCategory" class="form-control" id="quizCategory"  placeholder="Quiz Catergory" required="required">
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

							<!--QUIZ IMAGE - NOT REQUIRED-->
								<div class="form-group row">
								   <label for="quizIconToUpload" class="col-sm-2 col-form-label">Quiz Icon </label>
								   <div class="col-sm-10">
									   <label class="input-group-btn">
						                    <span class="btn ">
						                        Browse Files <input type='file' class='form-control' name='quizIconToUpload[]' id='quizIconToUpload' multiple='multiple' />
						                    </span>
						                </label>
					            	</div>
								</div>

							<!--QUIZ TAGS - NOT REQUIRED-->
								<div class="form-group row">
								   <label for="quizTags" class="col-sm-2 col-form-label">Quiz Tags<br/> (Comma Separated) </label>
								   <div class="col-sm-10">
								    	<input type="text" name="quizTags" class="form-control" id="quizTags" placeholder="Queer Themes, Why Did I Make This?, Romance" />
								   </div>
								</div>

							<!--PLACE WHERE JAVASCRIPT & JQUERY WILL APPEND QUESTIONS (IN ADDQUESTION.JS)-->
								<div class="form-group row">
									<div id="questionsList" class="container"></div>
								</div>

							<!--ADD QUESTION & END QUIZ BUTTONS-->
								<div class="form-group row">
						   			<p class='col-6 btn float-right' id="addQ" name='addQ'>Add Question <span class="icon add-quiz"></span></p>
						   			<p class='col-6 btn float-right' id="lastQ" name='lastQ'>No More Questions <!-- <span class="icon add-quiz"></span> --></p>
						  		</div>
						
						</div>
						
						<!--SUBMISSION FUNCTIONALITY-->
							<input type="hidden" id="quizMadeTime" name="quizMadeTime" value=""/>
							<input type='submit' class='btn float-right' value='Upload Quiz' name='submitQuiz' id="subBut" style='opacity: 0' />
							<small id='uploadHelp' class='text-danger'></small><br/>
					</form>
				</div>
			</div>
		</div>

	<script type="text/javascript" src="JS/addQuestion.js"></script>
	<script type="text/javascript" src="JS/logOut.js"></script>
</body>
</html>