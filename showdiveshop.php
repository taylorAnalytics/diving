<?php
/* The purpose of this script is to display the information about the selected dive shop. It will:
 * - pass the shopId number
 * - retrieve all information about the dive shop from mysql database (shop data, offer data, galleries, instructors(?), articles authored by the dive shop)
 * - present the data in specific sections
 * - have a "back to search" button
 * - have action buttons that will allow the user doing some stuff with the dive shop profile (such as add it to the trip or to the plan)
 */

// Need the utilities file
require('includes/utilities.inc.php');

// Define the title & display the heading
define('TITLE', 'Dive shop profile');
include('includes/header.inc.php');

// Retrieve data from mysql into a DiveShop object
// Create the query
$shop=new DiveShop($_GET['email']);
$shop->getUserData();
$shop->getOffer($shop->userData['userId']);

// Include the view with the left hand side menu
include('views/showdiveshop.html');

if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'show':
			include('views/showdiveshop/show.php');
			break;
		case 'offer':
			include('views/showdiveshop/offer.php');
			break;
		case 'articles':
			include('views/showdiveshop/articles.php');
			break;
		case 'galleries':
			include('views/showdiveshop/galleries.php');
			break;
		case 'action':
			include('views/showdiveshop/action.php');
			break;
		case 'addtotrip':
			include('views/showdiveshop/addtotrip.php');
			break;
		case 'addtoplan':
			include('views/showdiveshop/addtoplan.php');
			break;
	}
}

include('includes/footer.inc.php');
?>