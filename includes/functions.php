<?php

// Create the function to validate the registration form for the diver:
function validateDiver() {
	// Reference to global $validated & $dateOfBirth variables
	global $validated;
	global $dateOfBirth;
	// Loop through the $_POST variable & check if any of the fields have been left empty
	foreach ($_POST as $key => $value) {
		if (empty($_POST["$key"])) {
			$validated = FALSE;
		} // End of IF	
	} // End of foreach
	// Check if the passwords match
	if ($_POST['password1'] != $_POST['password2']) {
		$validated = FALSE;
		echo '<p class="error">Your passwords do not match</p>';
	}
	// Print a user prompt if the form had not been validated
	if ($validated == FALSE) {
		echo '<p class="error">Please fill in all the fields</p>';
	}
	// Create the $dateOfBirth variable if the form had been validated
	if ($validated) {
		$dateOfBirth = date('Y-m-d', strtotime("{$_POST['dayOfBirth']}-{$_POST['monthOfBirth']}-{$_POST['yearOfBirth']}"));
	}
}

// A function to validate the user if the user is an instructor
function validateInstructor() {
	// Reference to global $validated & $dateOfBirth variables
	global $validated;
	global $dateOfBirth;
	// Loop through the $_POST variable & check if any of the fields have been left empty
	foreach ($_POST as $key => $value) {
		if (empty($_POST["$key"])) {
			$validated = FALSE;
		} // End of IF	
	} // End of foreach
	// Check if the passwords match
	if ($_POST['password1'] != $_POST['password2']) {
		$validated = FALSE;
		echo '<p class="error">Your passwords do not match</p>';
	}
	// Print a user prompt if the form had not been validated
	if ($validated == FALSE) {
		echo '<p class="error">Please fill in all the fields</p>';
	}
	// Create the $dateOfBirth variable if the form had been validated
	if ($validated) {
		$dateOfBirth = date('Y-m-d', strtotime("{$_POST['dayOfBirth']}-{$_POST['monthOfBirth']}-{$_POST['yearOfBirth']}"));
	}
}

function validateShop() {
		// Reference to global $validated & $dateOfBirth variables
	global $validated;
	global $dateOfBirth;
	// Loop through the $_POST variable & check if any of the fields have been left empty
	foreach ($_POST as $key => $value) {
		if (empty($_POST["$key"])) {
			$validated = FALSE;
		} // End of IF	
	} // End of foreach
	// Check if the passwords match
	if ($_POST['password1'] != $_POST['password2']) {
		$validated = FALSE;
		echo '<p class="error">Your passwords do not match</p>';
	}
	// Print a user prompt if the form had not been validated
	if ($validated == FALSE) {
		echo '<p class="error">Please fill in all the fields</p>';
	}
}

function findKey($products, $field, $value) {
	foreach ($products as $key => $product) {
		if ($product[$field] == $value)  {
			return $key;
		}
	}
}

function sortByCourseAsc($a, $b) {
	global $course;
	return ($a['offerData'][$course] - $b['offerData'][$course]);
}

function sortByCourseDesc($a, $b) {
	global $course;
	return ($b['offerData'][$course]-$a['offerData'][$course]);
}

?>