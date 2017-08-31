<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php if (defined('TITLE')) {
		echo TITLE;
	} else {
		echo 'Diving is easy';
	}?></title>
	
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<script src="js/javascript.js"></script>
</head>
<body>
	<header>
		<h1>Diving is easy<span>All you need to know to scuba dive</span></h1>
			<nav>
				<ul>
					<li><a href="index.php">Home</a></li><li><a href="blog.php">Blog</a></li>
				
				<?php
				if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == TRUE) {
					switch ($_SESSION['userType']) {
						case 'Diver':
							echo '<li><a href="diverprofile.php">Profile</a></li><li><a href="trips.php">Trips</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="diveshopsearch.php">Search a dive shop</a></li>';
							break;
						case 'Instructor':
							echo '<li><a href="instructorprofile.php">Profile</a></li><li><a href="trips.php">Trips</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="jobsearch.php">Search for a job</a></li>';
							break;
						case 'DiveShop':
							echo '<li><a href="diveshopprofile.php">Profile</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="employeesearch.php">Search an employee</a></li>';
							break;
					} // End of switch	
					echo '<a href="logout.php">Logout</a>';
				} else {
					echo '<a href="login.php">Login</a>';
				} // End of IF
				?>
				</ul>
			</nav>
	</header>
<!-- start changeable content -->
	