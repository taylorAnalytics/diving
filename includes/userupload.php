<?php
/* This script uploads the user from the $_SESSION & gets their data */

if (isset($_SESSION['userType'])) {
	switch ($_SESSION['userType']) {
		case 'Diver':
			$diver = $_SESSION['diver'];
			$diver->getUserData();
			break;
		case 'Instructor':
			$instructor = $_SESSION['instructor'];
			$instructor->getUserData();
			break;
		case 'DiveShop';
			$diveShop = $_SESSION['diveShop'];
			$diveShop->getUserData();
			break;
	} // End of SWITCH
} // End of IF

?>