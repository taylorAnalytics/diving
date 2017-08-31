<?php
/* This script will present the diver's profile with a few options to choose. The options will be as follows:
 * - show diver's profile - just a viewing page.
 * - edit - possibility to edit all the fields (except the email & password)
 * - dive trips - where you can store the details of your recent dive trips & make them public or not (kinda like facebook, but focused on diving)
 * - dive plans - a webpage to plan the dive trips - directions, costs, etc.
 * - favorite articles - kinda like instapaper, but within my webpage
 */

require('includes/utilities.inc.php');

define('TITLE', 'Diver profile');
include('includes/header.inc.php');

// Upload the diver object from the session
$diver = $_SESSION['diver'];
// Inlude the main view, which includes the left side navigation for the options available within the diverprofile
include('views/diverprofile.html');
//
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'show':
			include('views/diverprofile/show.php');
			break;
		case 'edit':
			include('views/diverprofile/edit.php');
			break;
		case 'articles':
			include('views/diverprofile/articles.php');
			break;
		case 'write':
			include('views/diverprofile/write.php');
			break;
		case 'manage':
			include('views/diverprofile/manage.php');
			break;
		case 'editarticle':
			include('views/diverprofile/editarticle.php');
			break;
		case 'submit':
			include('views/diverprofile/submit.php');
			break;
	}
}

include('includes/footer.inc.php');

?>