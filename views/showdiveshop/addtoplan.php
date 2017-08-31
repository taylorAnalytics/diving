<?php
/* The purpose of this script will be to add the dive shop to a plan that is already in the database. It will:
 * - get the list of all user's plans from mysql database
 * - turn the list into an array
 * - present the form to select one plan
 * - save the information into mysql database into table plans
 * - redirect back to the dive shop page
 */

// Get the list of all user's plans from mysql:
// Define the array with the plans:
$plans=[];
// Create the query
$q='SELECT planId, title, destination FROM plans WHERE userId=:userId ORDER BY dateUpdated DESC';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array(':userId'=>$_SESSION['userId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($result=$stmt->fetch()) {
		$plans[]=$result;
	}
}

// Manage form submission:
if (isset($_POST['submit'])) {
	$problem=FALSE;
	// Check if the plan was selected
	if (!isset($_POST['planId'])) {
		$problem=TRUE;
		echo '<p class="error">Please select a trip to add the dive to</p>';
	}
	if (!$problem) { // The form has been submitted correctly
		// Check if the shopId is not already associated with the plan (is in the plan's database field)
		// Retrieve the list of all dive shops associated & turn it into an array
		$shopsInPlan=[]; // Define the array that will hold the diveshops
		$q='SELECT diveShops FROM plans WHERE planId=:planId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':planId'=>$_POST['planId']));
		if ($r) { // The query has been succesfully run
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($result=$stmt->fetch()) { // The data has been succesfully fetched
				$shopsInPlan=explode(',',$result['diveShops']);
			}
		}
		// Check if the shopId is in the array of the dive shops in plan
		if (in_array($shop->userData['shopId'], $shopsInPlan)) { // The dive shop is in the array, therefore already is in plans database
			echo '<p class="error">This dive shop has already been added to this plan before</p>';
		} else { // The dive shop is new to this plan
			// Add the dive shop to the database
			// Add the dive shop to the array
			if (count($shopsInPlan)==1) { // There is only one element of the array
				if ($shopsInPlan[0]=='') { // The element is an empty string, meaning there are no dive shops in the plan
					$shopsInPlan[0]=$shop->userData['shopId'];
				} else {
					$shopsInPlan[]=$shop->userData['shopId'];
				}
			} else {
				$shopsInPlan[]=$shop->userData['shopId'];
			}
			// Turn the array into a string
			$diveShops=implode(',',$shopsInPlan);
			// Create the query to update the mysql database field
			$q='UPDATE plans SET diveShops=:diveShops WHERE planId=:planId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':diveShops'=>$diveShops, ':planId'=>$_POST['planId']));
			if ($r) { // The query has been susccesfully run
				echo '<p class="error">The dive shop has been added to the plan</p>';
			} else {
				echo '<p class="error">Something went wrong. Could not add the dive shop to the plan</p>';
			}
		}
	}
}

echo '<section class="gallery"><div>';
// Check if there are any trips in the database
if (!empty($plans)) {
	echo '<h4>Add the dive shop to a plan</h4>';
	echo '<form action="showdiveshop.php?page=addtoplan&email='.$shop->userData['email'].'" method="post">';
	echo '<p><select name="planId">';
	echo '<option selected disabled>Select a plan</option>';
	foreach ($plans as $key=>$plan) {
		echo "<option value=\"{$plan['planId']}\">".$plan['title'].', '.$plan['destination'].'</option>';
	}
	echo '</select></p>';
	echo '<p><input type="submit" name="submit" value="Add to the plan" /></p>';
} else {
	echo '<p class="error">You do not have any plans you can add the dive shop to</p>';
}

?>