<?php
/* This script will enable the user to manage their dive trips. It wll contain:
 * - a link to a file with the menu to create trips, view trips & plan trips views
 * - show specific page based on $_GET variable value
 */
 
// Need the utilities file
require('includes/utilities.inc.php');
// Define the title & include the header
define('TITLE', 'Dive trips');
include('includes/header.inc.php');
// Include the file with the left-side menu
include('views/trips.html');

if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'view':
			include('views/trips/view.php');
			break;
		case 'plan':
			include('views/trips/plan.php');
			break;
		case 'create':
			include('views/trips/create.php');
			break;
		case 'addadive':
			include('views/trips/addadive.php');
			break;
		case 'edit':
			include('views/trips/edit.php');
			break;
		case 'delete':
			include('views/trips/delete.php');
			break;
		case 'deleteshop':
			include('views/trips/deleteshop.php');
			break;
		case 'createplan':
			include('views/trips/createplan.php');
			break;
	}
}


// Include the footer
echo '</section>'; // End the section that starts the diverprofile.html - the one before the menu
include('includes/footer.inc.php');
?>
