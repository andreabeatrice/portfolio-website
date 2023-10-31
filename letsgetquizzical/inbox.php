<?php 
	include 'conn.php' ;

	include 'logged-in.php';

	include 'redirect.php' ;

	$userInfo = li($mysqli)[0];

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>let's get quizzical • inbox</title>

		<?php require 'head.php'; ?>
	</head>
	<body id="body">
		<?php require 'header.php'; ?>


		<div class="container">
			<div class="card mt-3" id="quizMaker-card">
				<!--PAGE HEADING-->
					<div class="container mt-2">
						<h5 class="card-title" id="quiztitle">
							<span id="ch-Heading" ><?php echo $userInfo["name"]." ".$userInfo["surname"]."'s" ?> Inbox</span>
						</h5>
						<h6 class="card-subtitle mb-1 text-muted" id="quizSub"></h6>
						<hr/>
			    	</div>

				<!--CONVERSATIONS LIST-->
					<div class="card-body">
						<ul class="list-group">
							<?php
								$chatsQ = "SELECT * FROM tbchat WHERE conversation_code LIKE '%".$_COOKIE['user']."%'";
								$cq = mysqli_query($mysqli, $chatsQ);
								$retA = mysqli_fetch_all($cq, MYSQLI_ASSOC);
								$chatsArray = array();
								foreach ($retA as $key) {
									array_push($chatsArray, $key['conversation_code']);
								}

								$chatsArray = array_unique($chatsArray);
								$numChats = 0;

								foreach ($chatsArray as $key) {
									$u = explode("a", $key);
									echo '<a href="personalMessage.php?rid='.$u[0].'a'.$u[1].'" class="card-link ml-4">';
									echo "<li class='list-group-item'>";

									if (strcmp($u[0], $_COOKIE['user'])) {
										$u = $u[0];
									}
									else {
										$u = $u[1];
									}

									$fC = "SELECT * FROM tbaccounts WHERE user_id='".$u."'";
									$fcA = $mysqli->query($fC);

									if($owner = mysqli_fetch_array($fcA)){
										echo '<div class="profile-pic float-left mr-1" id="profileIcon" style="background-image: url(gallery/'.$owner['user_image'].')" alt="..."></div>';
										echo '<h6 class="card-title mt-3" id="userName">'.strtok($owner['email_address'], '@').'';

										$chatsQ = "SELECT * FROM tbchat WHERE conversation_code LIKE '%".$_COOKIE['user']."%' AND sent_to='".$_COOKIE['user']."' AND seen='0'";
										$cq = mysqli_query($mysqli, $chatsQ);
										$retA = mysqli_fetch_all($cq, MYSQLI_ASSOC);
										$cA = array();
										foreach ($retA as $j) {
											array_push($cA, $j['conversation_code']);
										}

										$cA = array_unique($cA);

										$c = array_values($cA);

										foreach ($c as $j) {
											if (str_contains($j, $key)) {
												echo '<span class="float-right"><i class="fas fa-envelope"></i></span></h6>';
											}
											
										}

									}
									echo '</li></a>';
									$numChats++;
								}

								//IF NUMCHATS == 0 (NO CONVERSATIONS STARTED)
									if ($numChats == 0) {
										echo '<a href="#" class="btn " id="delB"> Start Chat</a>';
									}
							?>
						</ul>
					</div>
			</div>
		</div>
		
		<script type="text/javascript" src="JS/logOut.js"></script>
	</body>
</html>