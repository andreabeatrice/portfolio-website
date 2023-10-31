<?php 

		echo '<div id="head">';
			echo '<header>';
				echo '<nav class="navbar navbar-expand-lg navbar-light navbar-custom">';
		  			echo '<a class="navbar-custom" href="home.php"><img src="media/icon-title-small.png"></a>';
		  			echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">';
		    			echo '<span class="navbar-toggler-icon"></span>';
		  			echo '</button>';

		  			echo '<div class="navbar-collapse collapse w-100" id="navbarTogglerDemo02">';
		    			echo '<ul class="navbar-nav ml-auto">';
		      			echo '<li class="nav-item my-2 my-sm-0">';
		        				echo '<a class="nav-link" href="quiz-maker.php">Create New Quiz <span class="icon add-quiz"></span></a>';
		      			echo '</li>';
		      			echo '<li class="nav-item my-2 my-sm-0">';
		        				echo '<a class="nav-link" href="list-maker.php">Create New List <span class="icon add-list"></span></a>';
		      			echo '</li>';
		      			echo '<li class="nav-item active mr-sm-2">';
		        				echo '<a class="nav-link" href="home.php">Home<span class="icon home"></span></a>';
		      			echo '</li>';
		      			echo '<li class="nav-item my-2 my-sm-0">';
		        			echo '<a class="nav-link" href="profile.php?user='.$_COOKIE["user"].'&view=quizzes">';
		        				echo 'Profile <span class="icon profile"></span>';
		        			echo '</a>';
		      			echo '</li>';
		      			echo '<li class="nav-item my-2 my-sm-0">';
		        				echo '<a class="nav-link" href="friends.php">Friends <span class="icon friends"></span></a>';
		      			echo '</li>';
		      			echo '<li class="nav-item my-2 my-sm-0">';
		        				echo '<a class="nav-link" onclick="logOut()">Log Out <span class="icon log-out"></span></a>';
		      			echo '</li>';
		      			echo '<li>';
		      				echo '<form id="hiddenButton" action="index.php" method="POST">';
		      					echo '<input type="hidden" name="loggedOut" value="true"/>';
		      				echo '</form> ';
		      			echo '</li>';
		    			echo '</ul>';
		  			echo '</div>';
				echo '</nav>';
		 		
		 		$u = $_SERVER["SCRIPT_FILENAME"];

		 		if (strpos($u, 'home') !== false) {
		  			echo '<div id="homeNav" class="navbar-collapse">';
		  			echo '<ul class="navbar-nav ml-auto " id="localGlobal">';
		  				echo '<li class="nav-item col-5 my-sm-0 float-left">
		        				<a class="nav-link" href="home.php?feed=local">Local <span class="icon local"></span></a>
		      		  		  </li>
		      		  		  <li class="nav-item col-5 my-sm-0 float-left">
		        				<a class="nav-link" href="home.php?feed=global">Global <span class="icon global"></span></a>
		      		  		  </li>
		      		  		  <li class="nav-item col-2 my-sm-0 float-left">
		      		  		  	<div class="row" aria-describedby="tooltip" id="tooltipB">
			      		  		  	<form id="hiddenSearch" action="search.php" method="GET">
			        					<input type= "text"class="form-control" name="search" placeholder="quiz name, user name, tag" id="sBar" onclick="showPopper()" onblur="hidePopper()"/>
			        					<div id="tt" role="tooltip">Hint: Press Enter to search</div><br/>
			        				</form>
		        				</div>
		      		  		  </li>';
		  			echo '</ul>';
		  			echo '</div>';
		  		}
			echo '</header>';
		echo '</div>';

?>