<?php
/* This script will show the detailed information of the specific trip. It will:
 * - Retrieve all the informations from the MySQL database
 * - Present them in some fashion
 * - Have an "edit" button that would link to edit.php
 * - Have 2 buttons: add a dive & view dives from the trip
 * - Display the gallery (should it be the link to the gallery, or the photos from the gallery?)
 */

// Start up the html formatting:
echo '<section class="profile">';
echo '<a href="trips.php?page=view" class="button-2">Back to view trips</a><a href="trips.php?page=edit&tripId='.$_GET['tripId'].'" class="button-2">Edit trip</a>';
 
 // Create the query to retrieve all the info from the MySQL database:
$q = 'SELECT * FROM trips WHERE tripId=:tripId';
$stmt=$pdo->prepare($q);
$r = $stmt->execute(array(':tripId' => $_GET['tripId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'DiveTrip');
	$diveTrip = $stmt->fetch();
	echo '<div class="trip">';
	echo "<h2>$diveTrip->title</h2>
		<h4>$diveTrip->destination</h2>
		<p>From: $diveTrip->startDate To: $diveTrip->endDate</p>
		<p>Number of dives: $diveTrip->noOfDives.</p>
		<p>Maximal depth reached: $diveTrip->maxDepth $diveTrip->units</p><br>
		<p><strong>Description:</strong> <br>$diveTrip->description</p><br>
		";
	
	echo '<h4>Dives logged individually:</h4>
		<p><a class="button-2" href="trips.php?page=addadive&tripId='.$diveTrip->tripId.'">Add a new dive</a><a id="dive-button" class="button-3" onclick="showDives();">Show dive list</a></p>';
		
		// Query to retrieve dives from the database
		$q = 'SELECT * FROM dives WHERE tripId=:tripId ORDER BY date ASC, timeIn ASC';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':tripId' => $_GET['tripId']));
		if ($r) { // The query has been executed
			// A table that will display key elements
			echo '<table id="hidden-dive" class="dives"><tr><th>No.</th><th>Date</th><th>Depth</th><th>Time</th><th>Gas</th><th>Dive Site</th><th>Dive shop</th></tr>';
			$diveList=[];
			$i = 1;
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Dive');
			while ($dive = $stmt->fetch()) {
				echo '<tr><td>'.$i.'</td><td>'.$dive->date.'</td><td>'.$dive->maxDepth.' '.$dive->units.'</td><td>'.$dive->diveTime.' min.</td><td>'.strtoupper($dive->gas).'</td><td>'.$dive->diveSite.'</td><td>'.$dive->shopName.'<td><a class="button" href="trips.php?page=view&tripId='.$_GET['tripId'].'&diveId='.$dive->diveId.'">Show</a></td></tr>';
				$i++;
				$diveList[] = $dive->diveId;
			}
			$_SESSION['diveList'] = $diveList;
			echo '</table>';
		}
	
	// Show the list of diveshops associated with this dive trip
	echo '<h4>Dive Shops, that were used during this trip:</h4>';
	// Check if there were any dive shops associated in the database:
	// Define the list of dive shops used during the trip:
	$diveShops=[];
	// Create the query:
	$q='SELECT * FROM shopstrips WHERE tripId=:tripId';
	$stmt=$pdo->prepare($q);
	$r=$stmt->execute(array('tripId'=>$_GET['tripId']));
	if ($r) { // The query has been executed succesfully
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$i=0;
		while ($result=$stmt->fetch()) { // Retrieving each dive shop that has been added to the trip
			// Check which of the results it is & if it's the first one then open up the table
			if ($i==0) {
				echo '<table class="result">
				<tr><th>Dive shop name</th><th>Number of dives</th><th>Dive shop profile</th><th>Website</th><th>Delete</th></tr>';
			}
			$i++;
			// Retrieve detailed information about the dive shop from the database
			$q2='SELECT * FROM diveshops WHERE shopId=:shopId';
			$stmt2=$pdo->prepare($q2);
			$r2=$stmt2->execute(array(':shopId'=>$result['shopId']));
			if ($r2) { // The query has been succesfully run
				$stmt2->setFetchMode(PDO::FETCH_ASSOC);
				while ($result2=$stmt2->fetch()) {
					echo "<tr><td>{$result2['shopName']}</td><td>{$result['noOfDives']}</td>
					<td><a href=\"showdiveshop.php?email={$result2['email']}&page=show\">View</a></td>
					<td><a href=\"{$result2['website']}\" target=\"_blank\">".substr($result2['website'],7)."</a></td>
					<td><a href=\"trips.php?page=delete&shopId={$result2['shopId']}&tripId={$diveTrip->tripId}\">Delete</a></tr>";
				}
			}
		}
		echo '</table>';
	}
	echo '<p><a class="button-2" href="diveshopsearch.php">Add a dive shop</a></p>';
		
		
	// JavaScript to show & hide the table
		
	if($diveTrip->galleryId != null) {
		// Retrieve gallery information from MySQL database:
		$q = 'SELECT * FROM galleries WHERE galleryId=:galleryId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':galleryId' => $diveTrip->galleryId));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
			if ($gallery = $stmt->fetch()) {
				echo '<h4>Gallery:</h4>';
				echo '<div class="gallery-tile"><a href="gallery.php?page=view&galleryId=' . $gallery->galleryId . '">';
				echo '<div class="image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'" alt="' . $gallery->mainImage . '"/></div>';
				echo '<div class="gallery-info">';
				echo "<h4>$gallery->title</h4>";
				if ($gallery->public == '1') { echo '<p>Public</p>';} else { echo '<p>Private</p>';}
				echo '<p>' . date('F j, Y', strtotime($gallery->dateCreated)) . '</p>';
				echo '</div></a></div>';	
			}
		}
		
		
	}
}

// Show a delete button that redirects to the "delete" page
echo '<a class="button-3" onclick="showDiv();">Delete trip</a>
<div id="delete-dive"><p class="hidden">Are you sure you want to delete this trip?</p>
<a href="trips.php?page=delete&tripId='.$_GET['tripId'].'" class="button-2">Yes</a>
<a class="button-3" onclick="hideDiv();">No</a></div>';
echo '</section>';
?>
