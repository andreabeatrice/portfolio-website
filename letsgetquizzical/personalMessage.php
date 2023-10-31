<?php 
	include 'conn.php';
	include 'logged-in.php';

	include 'redirect.php' ;

	//GET PROFILE PICTURES OF TWO USERS IN CHAT
		$rid = isset($_GET['rid']) ? $_GET['rid'] : null;
		$users = explode("a", $rid);
		$userpics = array();

		$added = "SELECT * FROM `tbaccounts` WHERE user_id='".$users[0]."' OR user_id='".$users[1]."'";
		$array = mysqli_query($mysqli, $added);
		$x = mysqli_fetch_all($array, MYSQLI_ASSOC);

		foreach ($x as $val) {
			array_push($userpics, $val["user_image"]);
		}

	//IF SOME MESSAGES WERE UNSEEN, MARK THEM AS SEEN
		$seenQuery = "UPDATE tbchat SET seen='1' WHERE sent_to= '".$_COOKIE['user']."' AND conversation_code='".$users[0].'a'.$users[1]."'";
		
		if ($mysqli->query($seenQuery) === TRUE) {

		} else {

		}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • chat</title>

		<?php require 'head.php'; ?>

		<!--TEXT CHAT STYLING-->
			<style type="text/css">
				form {
					display: flex;
				}

				input{
					font-size: 1.2rem;
				}
			</style>
	</head>
	
	<body id="body">
		<?php require 'header.php'; ?>

		<div class="container">
			<div class="card my-3" id="quizMaker-card">

				 <!--CHAT HEADING (WITH ICONS OF USERS IN CHAT)-->
				<div class="container mt-2 ">
					<h5 class="card-title" id="quiztitle">
						<?php 
							echo '<div class="profile-pic float-left" id="profileIcon1" style="background-image: url(gallery/'.$userpics[0].')"></div>';
							echo '<div class="profile-pic float-left" id="profileIcon2" style="background-image: url(gallery/'.$userpics[1].')"></div>';
						?> 
						<span id="ch-Heading" >Chat</span>
					<hr/>
				</div>

				<div class="card-body ">
					<!--FOR TEXTCHAT.JS TO APPEND PREVIOUS MESSAGES-->
					<div class="input-group mb-3" id="messages" ></div>
					<form>
						<input type="text" class="form-control" id="message" autocomplete="off" autofocus placeholder="Type Message..." aria-label="Recipient's username" aria-describedby="basic-addon2" >
								
						<div class="input-group-append">
							<input type="submit" value="send" class="btn">
						</div>
								
						<input type="hidden" id="sender" name="sender" value=<?php echo'"'.$_COOKIE['user'].'"' ?>>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="JS/textChat.js"></script>
		<script type="text/javascript" src="JS/logOut.js"></script>
		<script type="text/javascript">
			//
				scrollToBottom = () => {
			        document.getElementById("messages").scrollTo(0, document.getElementById("messages").scrollHeight);
			    }

			    history.scrollRestoration = "manual";
			    window.onload = scrollToBottom;
		</script>
	</body>
</html>