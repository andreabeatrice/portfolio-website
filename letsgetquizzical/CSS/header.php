<?php 



?>
<!DOCTYPE html>
<html>
<body>
	<div id="home_Background">
		
	</div>
	<header>
		<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
		  <a class="navbar-custom" href="#"><img src="media/icon-title-small.png"></a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="navbar-collapse collapse w-100" id="navbarTogglerDemo02">
		    <ul class="navbar-nav ml-auto">
		      <li class="nav-item active mr-sm-2">
		        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
		      </li>
		      <li class="nav-item my-2 my-sm-0">
		        <a class="nav-link" href="profile.php">Profile</a>
		      </li>
		      <li class="nav-item my-2 my-sm-0">
		        <a class="nav-link" onclick="logOut()">Log Out</a>
		      </li>
		      <li>
		      	<form id="hiddenButton" action="index.php" method="POST">
		      		<input type="hidden" name="loggedOut" value="true"/>
		      	</form>
		      	 
		      </li>
		    </ul>
		  </div>
		</nav>
	</header>

	<script type="text/javascript">
		function logOut() {
			document.getElementById("hiddenButton").submit();
		}

	</script>
</body>
</html>