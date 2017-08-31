<?php
/* This scrip will enable editing the trip & individual dive details.
 * It will redirect to either editdive.php or edittrip.php, depending on $_GET[] value */
 
// Check which one should be edited - trip or dive:
if (isset($_GET['tripId'])) {
	if (isset($_GET['diveId'])) {
		include('views/trips/editdive.php');
	} else {
		include('views/trips/edittrip.php');
	}
}


?>