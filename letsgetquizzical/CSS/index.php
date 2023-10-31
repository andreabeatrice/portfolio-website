<?php
	if (isset($_POST['loggedOut'])) {
		unset($_COOKIE['LOGGED_IN']);
		unset($_COOKIE['EMAIL']);
		unset($_COOKIE['PASS']);

	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>let's get quizzical</title>
	<meta name="author" content="Andrea Blignaut">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<link rel="stylesheet" type="text/css" href="CSS/splash.css">
	<link rel="icon" href="media/icon.ico" type="image/icon type">


	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body id="splash_body">
	<div class="background">
		<?php 
			for ($i=0; $i < 200; $i++) { 
				$r = rand(1,3);
				if ($r == 1) {
					echo '<div class="questionmark fade-in-fwd-6s" id="bq-'.$i.'"><img src="media/icon1.png"></div>';
				}
				else if($r==2){
					echo '<div class="questionmark fade-in-fwd-6s" id="bq-'.$i.'"><img src="media/icon2.png"></div>';
				}
				else {
					echo '<div class="questionmark fade-in-fwd-6s" id="bq-'.$i.'"><img src="media/icon3.png"></div>';
				}
				
			}
		?>
	</div>

	<div class="flex-container">
		<img src="media/title-icon-tagline.png" class="fade-in-fwd " id="name"/>
		<button class="fade-in-fwd btn" id="continue-to-site" onclick="scrollToDiv()">continue to site <i class="fa fa-angle-right"></i></button>
	</div>

	<div id="div2">
		<div class="background">
			<?php 
				for ($i=0; $i < 200; $i++) { 
					$r = rand(1,3);
					if ($r == 1) {
						echo '<div class="questionmark fade-in-fwd-6s" id="bqrubine-'.$i.'"><img src="media/icon1.png"></div>';
					}
					else if($r==2){
						echo '<div class="questionmark fade-in-fwd-6s" id="bqrubine-'.$i.'"><img src="media/icon2.png"></div>';
					}
					else {
						echo '<div class="questionmark fade-in-fwd-6s" id="bqrubine-'.$i.'"><img src="media/icon3.png"></div>';
					}
					
				}
			?>
		</div>
		<div class="flex-container">
			<h1 class="h1">Login/Register</h1>
			<section id="forms">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="card">
							<h3><div class="card-header">Login</div></h3>
							<div class="card-body">
								<form action="home.php" method="POST">
									<fieldset>
										<div class="row">
											<div class="col-12 col-lg-6">
												<label for="loginEmail">Email Address:</label>
												<input type="email" id="loginEmail" class="form-control" placeholder="name@email.com" name="email">
											</div>
											<div class="col-12 col-lg-6">
												<label for="loginPass">Password:</label>
												<input type="password" id="loginPass" class="form-control" placeholder="******" name="pass">
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-12">
												<button type="submit" class="btn btn-dark" name="login">Login <i class="fa fa-angle-right"></i></button>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 mt-3 mt-md-0">
						<div class="card">
							<h3><div class="card-header">Register</div></h3>
							<div class="card-body">
								<form action="home.php" method="POST">
									<fieldset>
										<div class="row">
											<div class="col-12 col-lg-6">
												<label for="regName">First Name:</label>
												<input type="text" id="regName" class="form-control" placeholder="Joan" name="fname">
											</div>
											<div class="col-12 col-lg-6">
												<label for="regSurname">Last Name:</label>
												<input type="text" id="regSurname" class="form-control" placeholder="Doe" name="lname">
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-12 col-lg-6">
												<label for="regEmail">Email Address:</label>
												<input type="email" id="regEmail" class="form-control" placeholder="joan.doe@gmail.com" name="email" autocomplete="email">
											</div>
											<div class="col-12 col-lg-6">
												<label for="regBirthDate">Date of Birth:</label>
												<input type="date" id="regBirthDate" class="form-control" name="date">
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-12 col-lg-6">
												<label for="regEmail">Create Password:</label>
												<input type="password" id="pass1" class="form-control" placeholder="******" name="pass">
											</div>
											<div class="col-12 col-lg-6">
												<label for="regEmail">Confirm Password:</label>
												<input type="password" id="pass2" class="form-control" placeholder="******">
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-12">
												<button type="submit" class="btn btn-dark" name="register">Register <i class="fa fa-angle-right"></i></button>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">

		function scrollToDiv(){
			$('html, body').animate({
			  scrollTop: $("#div2").offset().top
			});
		}

		const interval = setInterval(function() {
		   let x = Math.floor(Math.random() * (200 - 0 + 1) + 0);
		   let id = "bq-" + x;
		   let idrubine = "bqrubine-" + x;

		   let time = Math.floor(Math.random() * (4 - 0 + 1) + 0) +2;

		   if (document.getElementById(id).classList.contains("anim")) 
				restart(document.getElementById(id));

			if (document.getElementById(idrubine).classList.contains("anim")) 
				restart(document.getElementById(idrubine));
		   	
		   	setanim(document.getElementById(id), time);
		   	setanim(document.getElementById(idrubine), time);
		   
		 }, 100);

		function restart(id) {
			id.style.animation = 'none';
			setTimeout(() => {
				id.style.animation = '';
			}, 0);
		}

		function setanim(id, time) {
			id.style.opacity = "0";
		   	id.style.animation= "blink-1 "+time+"s both";
		   	id.classList.add("anim");
		}
	</script>

</body>
</html>