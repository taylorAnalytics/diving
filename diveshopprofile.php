<?php
/* This script will present the dive shop's profile with a few options to choose. The options will be as follows:
 * - show shop's profile - just a viewing page.
 * - edit - possibility to edit all the fields (except the password)
 * - shop's offer - how do I do that? Is that part of the shop's profile or a separate thing?
 * - favorite articles - kinda like instapaper, but within my webpage
 * - write & manage articles (if the user is also an author)
 */
// Need the utilities file:
require('includes/utilities.inc.php');
// Define the page's title & include the header
define('TITLE', 'Dive shop\'s profile');
include('includes/header.inc.php');

// Upload the dive shop's object from $_SESSION
$shop=$_SESSION['diveShop'];
$shop->getUserData();

// Include the file with the left side menu
include('views/diveshopprofile.html');
// Check, which page has been selected by the user and load the necessary file to show the page
if (isset($_GET['page'])) {
	switch($_GET['page']) {
		case 'show':
			include('views/diveshopprofile/show.php');
			break;
		case 'edit':
			include('views/diveshopprofile/edit.php');
			break;
		case 'offer':
			include('views/diveshopprofile/offer.php');
			break;
		case 'editoffer':
			include('views/diveshopprofile/editoffer.php');
			break;
		case 'articles':
			include('views/diverprofile/articles.php');
			break;
	} // End the SWITCH
} // End the page IF

// Need the footer
include('includes/footer.inc.php');
?>