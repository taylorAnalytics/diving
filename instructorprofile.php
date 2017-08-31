<?php
/* This script will present the instructor's profile with a few options to choose. The options will be as follows:
 * - show insructors's profile - just a viewing page.
 * - edit - possibility to edit all the fields (except the email & password)
 * - dive trips - where you can store the details of your recent dive trips & make them public or not (kinda like facebook, but focused on diving)
 * - dive plans - a webpage to plan the dive trips - directions, costs, etc.
 * - favorite articles - kinda like instapaper, but within my webpage
 * - write & manage articles (if the user is also an author)
 */
// Need the utilities file
require('includes/utilities.inc.php');

// Define the webpage title & include the header
define('TITLE', 'Instructor profile');
include('includes/header.inc.php');

// Upload the instructor object from the session
$instructor=$_SESSION['instructor'];

// Include the file with the left side menu
include('views/instructorprofile.html');
// Check, which page has been selected by the user and load the necessary file to show the page
if (isset($_GET['page'])) {
	switch($_GET['page']) {
		case 'show':
			include('views/instructorprofile/show.php');
			break;
		case 'edit':
			include('views/instructorprofile/edit.php');
			break;
		case 'articles':
			include('views/diverprofile/articles.php');
			break;

	} // End of swithc
} // End of IF that checks for the webpage selected

// Include the footer file
include('includes/footer.inc.php');

?>
