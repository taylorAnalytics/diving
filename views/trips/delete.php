<?php
/* This script will delete the trip or the dive. It will:
 * - read the $_GET variable to see what is being deleted - trip or dive
 * - create the respective object
 * - call the delete() method of the object
 * - unset the object
 * - have the button to go back to view trips or trip
 */

echo '<section class="profile">';
if (isset($_GET['tripId'])) {
	if (isset($_GET['diveId'])) { // We're deleting the dive
		// Create the dive object
		$dive = new Dive();
		if ($dive->delete($_GET['diveId'])) { // The delete() method has been succesfully run}
			echo '<p class="error">Your dive has been deleted from the database</p>';
		} else {
			echo '<p class="error">Something went wrong. Could not delete your dive</p>';
		}
	} elseif (isset($_GET['shopId'])) { // We're deleting the connection between the dive shop & the trip
		if (isset($_GET['sure']) && ($_GET['sure']==1)) { // If the user already clicked "Yes, delete" button
			// Create the mysql query to delete the connection
			$q='DELETE FROM shopstrips WHERE (tripId=:tripId) AND (shopId=:shopId)';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':tripId'=>$_GET['tripId'], ':shopId'=>$_GET['shopId']));
			if ($r) { // The query has been succesfully run
				echo '<p class="error">The dive shop has been deleted from the trip</p>';
			} else {
				echo '<p class="error">Something went wrong. Could not delete the dive shop from the trip</p>';
			}

		} else { // If the user has just been redirected here & has not clicked anything yet
			echo '<h4>Are you sure you want to delete the connection between the trip & the dive shop?</h4>';
			echo '<p><a class="button-2" href="trips.php?page=delete&shopId='.$_GET['shopId'].'&tripId='.$_GET['tripId'].'&sure=1">Yes, delete</a>
				<a class="button-2" href="trips.php?page=view&tripId='.$_GET['tripId'].'">No, go back to the trip</a></p>';
		}
	} else { // We're delting the trip
		$trip = new DiveTrip();
		if ($trip->delete($_GET['tripId'])) {
			echo '<p class="error">Your dive trip has been deleted from the database</p>';
		} else {
			echo '<p class="error">Something went wrong. Could not delete your dive trip</p>';
		}
	}
} elseif (isset($_GET['planId'])) { // We're deleting an element of the plan
	if (isset($_GET['shopId'])) { // We're deleting a dive shop
		if (isset($_GET['sure']) && ($_GET['sure']==1)) { // The user confirmed that they want to delete the dive shop from the plan
			// Delete the shopId from the mysql database
			// Retrieve all shopIds that are associated with the plan
			$diveShops=null;
			$shopsInPlan=[];
			$q='SELECT diveShops FROM plans WHERE planId=:planId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':planId'=>$_GET['planId']));
			if ($r) { // The query has been succesfully run
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				if ($result=$stmt->fetch()) {// The results have been retrieved
					$diveShops=$result['diveShops'];
					$shopsInPlan=explode(',',$diveShops);
				}
			}
			// Delete the shopId from the array with all the diveshops associated with the plan
			$key=array_search($_GET['shopId'], $shopsInPlan); // Find the position of the diveShop in the array
			unset($shopsInPlan[$key]);
			$diveShops=implode(',',$shopsInPlan);
			// Create the query to update the diveShops
			$q='UPDATE plans SET diveShops=:diveShops WHERE planId=:planId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':diveShops'=>$diveShops, ':planId'=>$_GET['planId']));
			if ($r) { // The query has been succesfully run
				echo '<p class="error">The dive shop has been deleted</p>';
			} else {
				echo '<p class="error">Something went wrong. Could not delete the dive shop from the plan</p>';
			}
		} else { // The user has not been asked yet if they were sure they wanted to delete the dive shop from the plan
			echo '<h4>Are you sure you want to delete the dive shop from the plan?</h4>';
			echo '<p><a class="button-2" href="trips.php?page=delete&shopId='.$_GET['shopId'].'&planId='.$_GET['planId'].'&sure=1">Yes, delete</a>
			<a class="button-2" href="trips.php?page=plan&planId='.$_GET['planId'].'">No, go back to the trip</a></p>';
		}
		
	}
}

echo '</section>';
?>