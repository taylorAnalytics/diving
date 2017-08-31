<?php
/* This website will be used to register a new user through the HTML_QuickForm2 */

// Need the utilities file:
require('includes/utilities.inc.php');
// Need the custom functions
include('includes/functions.php');
include('includes/lists.php');

define('TITLE', 'Register');
include('includes/header.inc.php');

// Validate the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Create the validation variable
	$validated = TRUE;
	// Create the date of birth variable
	$dateOfBirth = null;
	// Switch based on the hidden input type - userType
	switch ($_POST['userType']) {
		case 'Diver':
			validateDiver();
			break;
		case 'Instructor':
			validateInstructor();
			break;
		case 'DiveShop':
			validateShop();
			break;
	}
	// Print out the information that the user have been registered
	// Implement the actual registration scheme - create the user object, insert data into MySQL, etc.
	if ($validated) {
		switch ($_POST['userType']) {
			case 'Diver':
				$diver = new Diver($_POST['email']);
				$pass = hash('sha256', $_POST['password1']);
				if($diver->register($_POST['email'], $pass)) { // The registration process was succesfull
					echo '<p class="error">You have been registered</p>';
					unset($diver);
					$_POST=[];
				} else {
					echo '<p class="error">Something went wrong. We couldn\'t register you</p>';
				}// End of registration IF
				break;
			case 'Instructor':
				$instructor = new Instructor($_POST['email']);
				$pass = hash('sha256', $_POST['password1']);
				if($instructor->register($_POST['email'], $pass)) { // The registration process was successfull
					echo '<p class = "error">You have been registered</p>';
					unset($instructor);
					$_POST=[];
				} else {
					echo '<p class="error">Something went wrong. We couldn\'t register you</p>';
				}// End of registration IF
				break;
			case 'DiveShop':
				$diveShop = new DiveShop($_POST['email']);
				$pass = hash('sha256', $_POST['password1']);
				if($diveShop->register($_POST['email'], $pass)) { // The registration process was succesfull
					echo '<p class="error">You have been registered</p>';
					unset($diveShop);
					$_POST=[];
				} else {
					echo '<p class="error">Something went wrong. We couldn\'t register you</p>';
				}
		} // End of validation SWITCH
	} // End of $validated IF
} else {
	
}


include('views/register.html');
include('includes/footer.inc.php');
?>