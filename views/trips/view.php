<?php
/* This script will show all the trips & enable createing new ones. It will:
 * - Retrieve all the user's trips from the database
 * - Display the basic info of each trip
 * - link to the detaild view of a single trip
 * - have a button that would link to the "create new trip" page
 */

// Check if a specific page has been selected:
if (isset($_GET['tripId'])) {
	if (isset($_GET['diveId'])) {
		include('views/trips/viewdive.php');
	} else {
		include('views/trips/viewtrip.php');
	}
} else {

?>
<section class="profile">
	<div class="title">
		<h3>View your dive trips</h3>
		<p>Browse through your dive trips. Click on one to see the details, edit it or add dives</p>
	</div>
	<a href="trips.php?page=create" class="button-2">Create a new trip</a>
<?php

// Retrieve all the user's trips from the MySQL database
// Create the query
$q = 'SELECT * FROM trips WHERE userId=:userId ORDER BY endDate DESC';
$stmt=$pdo->prepare($q);
$r = $stmt->execute(array(':userId' => $_SESSION['userId']));
if ($r) { // The query has been succesfully executed
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'DiveTrip');
	while ($diveTrip = $stmt->fetch()) {
		echo '<div class="trip">
			<a class="button" href="trips.php?page=view&tripId='.$diveTrip->tripId.'">View</a>
			<a class="button" href="trips.php?page=edit&tripId='.$diveTrip->tripId.'">Edit</a>
		
		<h3>'.$diveTrip->title.'</h3>
		<p>Destination: '.$diveTrip->destination.'</p>
		<p>From: '.$diveTrip->startDate.' To: '.$diveTrip->endDate.'</p>
		</div>';
	}
}
}

echo '</section>';
?>