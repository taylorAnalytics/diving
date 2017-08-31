<?php
/* This script will present a personalized gallery. It will:
 * - Show the list of current user galleries
 * - The gallery can be private or public
 * - The list of the galleries will be tile-styled
 * - Each tile will contain the basic information about the gallery & one picture
 * - Clicking on the tile will link to a sub-page with all the pictures from this particular gallery
 * - The details of the gallery will be stored in mysql database
 * - The pictures themselves will be stored in data file system
 */
// Need the utilities file
require('includes/utilities.inc.php');
// Need the header
include('includes/header.inc.php');
 
// Include the main view with the navigation on the left:
include('views/gallery.html');
// Check if any of the pages has been selected from the menu on the left:
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'all':
			include('views/gallery/all.php');
			break;
		case 'new':
			include('views/gallery/new.php');
			break;
		case 'public':
			include('views/gallery/public.php');
			break;
		case 'view':
			include('views/gallery/view.php');
			break;
		case 'edit':
			include('views/gallery/edit.php');
			break;
		case 'publicview':
			include('views/gallery/publicview.php');
			break;
		case 'addtoplan':
			include('views/gallery/addtoplan.php');
			break;
	}
} else { // None of the specific sub-pages has been selected
	// Inlcude the welcome gallery page - a static page with key informations
	include('views/welcomegallery.html');	
}
 
 // Need the footer
 include('includes/footer.inc.php');
 ?>