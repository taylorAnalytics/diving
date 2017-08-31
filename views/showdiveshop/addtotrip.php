<?php
/* The purpose of this script will be to add the dive shop to a trip that is already in the database. It will:
 * - get the list of all user's trips from mysql database
 * - turn the list into an array
 * - present the form to select one trip
 * - have an input field for the number of dives done in that dive shop during that trip
 * - save the information into mysql database into table shopstrips
 * - redirect back to the dive shop page
 */

// Get the list of all user's trips from mysql:
// Define the array with the trips:
$trips=[];
// Create the query
$q='SELECT tripId, title, destination FROM trips WHERE userId=:userId';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array(':userId'=>$_SESSION['userId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($result=$stmt->fetch()) {
		$trips[]=$result;
	}
}

// Manage form submission:
if (isset($_POST['submit'])) {
	$problem=FALSE;
	// Check if the trip was selected
	if (!isset($_POST['tripId'])) {
		$problem=TRUE;
		echo '<p class="error">Please select a trip to add the dive to</p>';
	}
	if (!$problem) { // The form has been submitted correctly
		// Check if this connection already exists in the database
		// Create the query that checks if there is a connection with this shopId & tripId
		$q='SELECT conId FROM shopstrips WHERE (shopId=:shopId) AND (tripId=:tripId)';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array('shopId'=>$shop->userData['shopId'], 'tripId'=>$_POST['tripId']));
		if ($r) { // The query has been run succesfully
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result=$stmt->fetch();
			if (empty($result)) { // There is no connection in the database and it needs to be created
				// Create the query to insert the new connection into the database:
				$q2='INSERT INTO shopstrips (tripId, shopId, noOfDives) VALUES (:tripId, :shopId, :noOfDives)';
				$stmt2=$pdo->prepare($q2);
				$r2=$stmt2->execute(array('tripId'=>$_POST['tripId'], ':shopId'=>$shop->userData['shopId'], ':noOfDives'=>$_POST['noOfDives']));
				if ($r2) {
					echo '<p class="error">The dive shop has been added to a dive trip</p>';
				} else {
					echo '<p class="error">Something went wrong. Could not add the dive shop to the dive trip</p>';
				}
			} else { // There is such a connection in the database and it needs to be updated
				// Create the query to update the connection in the database:
				$q2='UPDATE shopstrips SET noOfDives=:noOfDives WHERE conId=:conId';
				$stmt2=$pdo->prepare($q2);
				$r2=$stmt2->execute(array(':noOfDives'=>$_POST['noOfDives'], ':conId'=>$result['conId']));
				if ($r2) {
					echo '<p class="error">The dive shop & dive trip connection in the database has been updated</p>';
				} else {
					echo '<p class="error">Something went wront, could not update the information in the databse</p>';
				}
			}
		}
		// If it does, than update it (the number of dives)
		// If it doesn't, than create it

	}
}

echo '<section class="gallery"><div>';
// Check if there are any trips in the database
if (!empty($trips)) {
	echo '<h4>Add the dive shop to a trip</h4>';
	echo '<form action="showdiveshop.php?page=addtotrip&email='.$shop->userData['email'].'" method="post">';
	echo '<p><select name="tripId">';
	echo '<option selected disabled>Select a trip</option>';
	foreach ($trips as $key=>$trip) {
		echo "<option value=\"{$trip['tripId']}\">".$trip['title'].', '.$trip['destination'].'</option>';
	}
	echo '</select></p>';
	echo '<p>Number of dives: <input type="number" name="noOfDives" /></p>';
	echo '<p><input type="submit" name="submit" value="Add to the trip" /></p>';
} else {
	echo '<p class="error">You do not have any trips you can add the dive shop to</p>';
}

?>