<?php
/* This script will display the information about the dive. It will:
 * - create a Dive object & retrieve data from MySQL database
 * - present data about the object in a table (similar to the create dive, except no form)
 * - have two buttons: go back to view trip & edit (go to edit dive view)
 * - have "show next" & "show previous" dive buttons
 */
// Start the section & print two main buttons
if (isset($_SESSION['diveList'])) {
	$diveList = $_SESSION['diveList'];
} else {
	$diveList = [];
}
echo '<section class="profile">';
echo '<a href="trips.php?page=view&tripId='.$_GET['tripId'].'" class="button-2">Back to view trip</a>
	<a href="trips.php?page=edit&tripId='.$_GET['tripId'].'&diveId='.$_GET['diveId'].'" class="button-2">Edit dive</a>';
// Check current dive's index
$diveKey = array_search($_GET['diveId'], $diveList);
$nextKey = $diveKey+1;
$previousKey = $diveKey-1;

if ($diveKey > 0) {
	echo '<a href="trips.php?page=view&tripId='.$_GET['tripId'].'&diveId='.$diveList[$previousKey].'" class="button-2">Previous dive</a>';
}
if ($diveKey < count($diveList) - 1) {
	echo '<a href="trips.php?page=view&tripId='.$_GET['tripId'].'&diveId='.$diveList[$nextKey].'" class="button-2">Next dive</a>';
}
	

// Create the dive object & get the dive data
$dive = new Dive();
$dive->getData($_GET['diveId']);

// Create the table with the dive info:
echo '<table class="profile">
	<tr><td>Date of the dive: </td><td>'.$dive->date.'</td></tr>
	<tr><td>Time in: </td><td>'.$dive->timeIn.'</td></tr>
	<tr><td>Time out: </td><td>'.$dive->timeOut.'</td></tr>
	<tr><td>Total dive time: </td><td>'.$dive->diveTime.'</td></tr>
	<tr><td>Maximum depth: </td><td>'.$dive->maxDepth.' '.$dive->units.'</td></tr>
	<tr><td>Gas used: </td><td>';
	if ($dive->gas == 'air') {echo $dive->gas;} else {echo (strtoupper($dive->gas).$dive->o2Level);}
	echo '</td></tr>
	<tr><td>Dive shop: </td><td>'.$dive->shopName.'</td></tr>
	<tr><td>Country of the dive: </td><td>'.$dive->country.'</td></tr>
	<tr><td>Dive site name: </td><td>'.$dive->diveSite.'</td></tr>
	<tr><td>Dive characteristics: </td><td>'.str_replace(',',', ',$dive->characteristics).'</td></tr>
	<tr><td>Marine life spotted: </td><td>'.$dive->marineLife.'</td></tr>
	<tr><td>Dive description: </td><td>'.$dive->description.'</td></tr>
	</table>';

	
echo '<a class="button-3" onclick="showDiv();">Delete dive</a>
<div id="delete-dive"><p class="hidden">Are you sure you want to delete this dive?</p>
<a href="trips.php?page=delete&tripId='.$_GET['tripId'].'&diveId='.$_GET['diveId'].'" class="button-2">Yes</a>
<a class="button-3" onclick="hideDiv();">No</a></div>';
echo '</section>';
?>