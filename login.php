<?php
/* This script will enable the user to log in into the service. It will:
 * - display a login form
 * - verify the form submission
 * - create the user object from user class
 * - create the loggedin true variable
 * - direct back to the home page or user profile with the loggedin=true set
 */

// Require the utilities file (?) - what do I put into the utilities file?
require('includes/utilities.inc.php');

define('TITLE', 'Log in');
include('includes/header.inc.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Check the userType
	$q = 'SELECT userType, userId FROM users WHERE email=:email';
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':email' => $_POST['email']));
	if ($r) {
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['userType'] = $result['userType'];
		$_SESSION['userId'] = $result['userId'];
	
	}
	
	// create a new object of the proper type (based on the userType) class & run the method logIn()
	switch ($_SESSION['userType']) {
		case 'Diver':
			$diver = new Diver($_POST['email']);
			$pass = hash('sha256', $_POST['password']);
			$diver->logIn($_POST['email'], $pass);
			break;
		case 'Instructor':
			$instructor = new Instructor ($_POST['email']);
			$pass = hash('sha256', $_POST['password']);
			$instructor->logIn($_POST['email'], $pass);
			break;
		case 'DiveShop':
			$diveShop = new DiveShop ($_POST['email']);
			$pass = hash('sha256', $_POST['password']);
			$diveShop->logIn($_POST['email'], $pass);
			break;
		default:
			echo '<p class="error">Your email or password are incorrect. Could no log you in</p>';
	}
	
	
	
} // End of form submission IF


include('views/login.html');
include('includes/footer.inc.php');
?>