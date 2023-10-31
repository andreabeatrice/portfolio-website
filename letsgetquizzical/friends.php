<?php 
	if(FALSE)// toggle to false after debugging
	{
	  ini_set( 'display_errors', 'true');
	  error_reporting(-1);
	}
	
	include 'conn.php';

	include 'redirect.php' ;

	//GET NUMBER OF FRIENDS
		$myListsQuery = "SELECT * FROM tbfriends WHERE friendship_creator= '".$_COOKIE["user"]."' OR friendship_acceptor='".$_COOKIE["user"]."'";
		$lists = mysqli_query($mysqli, $myListsQuery);
		$friends = mysqli_fetch_all($lists, MYSQLI_ASSOC);
		$numFriends = 0;
		foreach ($friends as $key) {
			if(strcmp($key['friendship_accepted'], 1) == 0)
				$numFriends++;
		}

	//IF USER JUST DENIED A FRIEND REQUEST
		if(isset($_POST['denyRequest'])){
			echo $_POST['reqFrom'];
			$denyQuery = "DELETE FROM tbfriends WHERE friendship_creator= '".$_POST['reqFrom']."' AND friendship_acceptor='".$_COOKIE["user"]."'";
			if ($mysqli->query($denyQuery) === TRUE) {
				header("Refresh:0");
			} else {

			}
		}

	//IF USER JUST ACCEPTED A FRIEND REQUEST
		if (isset($_POST['acceptRequest'])){
			$acceptQuery = "UPDATE tbfriends SET friendship_accepted='1' WHERE friendship_creator= '".$_POST['reqFrom']."' AND friendship_acceptor='".$_COOKIE["user"]."'";	

			if ($mysqli->query($acceptQuery) === TRUE) {
				header("Refresh:0");
			} else {

			}
		}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>let's get quizzical • friends</title>
		<meta name="author" content="Andrea Blignaut">

		<?php require 'head.php'; ?>
	</head>
	<body id="body">

		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card mt-3" id="quizMaker-card">
				<!--PAGE HEADING-->
					<div class="container mt-2">
						<h5 class="card-title" id="quiztitle"><?php echo $numFriends; ?> friends</h5>
						<h6 class="card-subtitle mb-1 text-muted" id="quizSub"></h6>
						<hr/>
			    	</div>
				<div class="card-body">
					<!--USER SEARCH FORM (BY EMAIL/USERNAME)-->
						<form id="friendForm" action="#">
							<div class="form-group row">
								<label for="addToList" class="col-sm-2 col-form-label">Search for a User:</label>
								<div class="col-sm-10 autocomplete">
								    <input type="text" name="addAList" class="form-control" id="addToList" placeholder="" maxlength="255" />
								</div>
							</div>
							
							<div class="form-group row">
								<ul id="quizList" ></ul>
							</div>
						</form>

					<!--USER'S FRIENDS LIST-->
						<h3>Your Friends</h3>

						<ul class="list-group list-group-flush">
							<!--ALL FRIENDS WHERE FRIENDSHIP HAS BEEN ACCEPTED (NOT REQUESTS)-->
								<?php
									foreach ($friends as $key) {
										if(strcmp($key['friendship_accepted'], 1) == 0){

											if (strcmp($key["friendship_creator"], $_COOKIE['user'])) {
												echo "<a href='profile.php?user=".$key["friendship_creator"]."&view=quizzes' class='card-link'>";
											}
											else {
												echo "<a href='profile.php?user=".$key["friendship_acceptor"]."&view=quizzes' class='card-link'>";
											}
											
											echo "<li class='list-group-item'>";
											$friendId;
											if($key["friendship_creator"] != $_COOKIE['user']){
												$friendId = $key["friendship_creator"];
											}
											else {
												$friendId = $key["friendship_acceptor"];
											}
											$friendDetails = "SELECT first_name,last_name,email_address,user_image FROM tbaccounts WHERE user_id='".$friendId."'";
											$fInfo = mysqli_query($mysqli, $friendDetails);
											$fInfoArray = mysqli_fetch_all($fInfo, MYSQLI_ASSOC);

											foreach ($fInfoArray as $k) {
												echo "<img src='gallery/".$k["user_image"]."' width='5%' style='border-radius:100%;'/>";
												echo "<p class='card-text d-inline ml-3'>".strtok($k['email_address'],  '@')."</p>";
											}
											echo "</li></a>";
										}
									}

									if ($numFriends == 0) {
										echo "<li class='list-group-item'>You have no friends yet. Search for users to add some.</li>";
									}
								?>
						</ul>

					<br/>

					<!--FRIEND REQUESTS THAT THE USER HAS SENT/ THAT HAVE BEEN SENT TO USER-->
					<h3>Friend Requests</h3>
					
					<ul class="list-group list-group-flush">
						<?php
							$numRequest = 0;
							foreach ($friends as $key) {
								
								if(strcmp($key['friendship_accepted'], 0) == 0 && $key["friendship_acceptor"] == $_COOKIE['user']){
									$numRequest++;							
									echo "<li class='list-group-item'>";
									echo '<form action="friends.php" method="POST">';
									$friendId;
									if($key["friendship_creator"] != $_COOKIE['user']){
										$friendId = $key["friendship_creator"];
									}
									else {
										$friendId = $key["friendship_acceptor"];
									}
									$friendDetails = "SELECT first_name,last_name,email_address,user_image FROM tbaccounts WHERE user_id='".$friendId."'";
									$fInfo = mysqli_query($mysqli, $friendDetails);
									$fInfoArray = mysqli_fetch_all($fInfo, MYSQLI_ASSOC);

									foreach ($fInfoArray as $k) {
										echo "<a href='profile.php?user=".$key["friendship_creator"]."&view=quizzes' class='card-link'>";
										echo "<img src='gallery/".$k["user_image"]."' width='5%' style='border-radius:100%;'/>";
										echo "<p class='card-text d-inline ml-3'>".strtok($k['email_address'],  '@')."</p></a>";
										echo "<input type='hidden' name='reqFrom' value='".$key['friendship_creator']."'/>";
										echo "<input type='submit' class='btn float-right' name='denyRequest' value='Deny Request'/>";
										echo "<input type='submit' class='btn float-right mr-2' name='acceptRequest' value='Accept Request'/>";

									}
									echo "</form></li>";
								}
								else if(strcmp($key['friendship_accepted'], 0) == 0 && $key["friendship_creator"] == $_COOKIE['user']){
									$numRequest++;							
									echo "<li class='list-group-item'>";
									$friendId;
									if($key["friendship_creator"] != $_COOKIE['user']){
										$friendId = $key["friendship_creator"];
									}
									else {
										$friendId = $key["friendship_acceptor"];
									}
									$friendDetails = "SELECT first_name,last_name,email_address,user_image FROM tbaccounts WHERE user_id='".$friendId."'";
									$fInfo = mysqli_query($mysqli, $friendDetails);
									$fInfoArray = mysqli_fetch_all($fInfo, MYSQLI_ASSOC);

									foreach ($fInfoArray as $k) {
										echo "<a href='profile.php?user=".$key["friendship_acceptor"]."&view=quizzes' class='card-link'>";
										echo "<img src='gallery/".$k["user_image"]."' width='5%' style='border-radius:100%;'/>";
										echo "<p class='card-text d-inline ml-3'>".strtok($k['email_address'],  '@')."</p></a>";
										echo "<button class='btn btn-look text-muted float-right' disabled='disabled' >Request Pending</button>";

									}
									echo "</li>";
								}
							}

							if ($numRequest == 0) {
								echo '<li class="list-group-item">You have no pending friend requests. Search for a user to make a request.</li>';
							}
						?>
					</ul>
				</div>
			</div>
		</div>

		<!--JAVASCRIPT FOR AUTOCOMPLETEING USERNAMES IN SEARCH-->
		<script type="text/javascript" src="JS/autocompleteUsers.js"></script>
		<script type="text/javascript" src="JS/logOut.js"></script>

		<script type="text/javascript">

			//FUNCTION TO SUBMIT FRIENDS SEARCH
				sub = () => {
					document.getElementById("friendForm").submit();
				}
		</script>
	</body>
</html>