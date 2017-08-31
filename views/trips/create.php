<?php
/* This script will enable creating a new trip. It will:
 * - contain a form to enter the data for the trip
 * - hadle the form
 * - use the "DiveTrip" class to create object & save them in the MySQL database
 */
// Include the lists.php to use the array of the months in the form
include('includes/lists.php');

echo '<section class="profile">
	<div class="title">
		<h3>Create your dive trip</h3>
		<p>Fill in the details of your dive trip & assign the gallery to it</p>
	</div>';

// Validate the form
if($_SERVER['REQUEST_METHOD'] == 'POST') {  
	$problem = FALSE;
	$startDate = null;
	$endDate = null;
	$galleryId = null;
	$maxDepth = null;
	$units = null;
	
	// Check for all the required fields
	if (empty($_POST['title'])) {
		$problem = TRUE;
		echo '<p class="error">Please enter the title of the trip</p>';
	}
	if (empty($_POST['destination'])) {
		$problem = TRUE;
		echo '<p class="error">Please enter the destination</p>';
	}
	if (empty($_POST['description'])) {
		echo '<p class="error">Please enter a short description of your trip</p>';
		$problem = TRUE;
	}
	if (empty($_POST['noOfDives'])) {
		$problem = TRUE;
		echo '<p class="error">Please enter the number of dives you did during the trip</p>';
	}
	// Validate the start date
	if (isset($_POST['startDay']) && isset($_POST['startMonth']) && isset($_POST['startYear'])) { // All the elements of the date have been submitted
		$startDate = date('Y-n-j', strtotime("{$_POST['startYear']}-{$_POST['startMonth']}-{$_POST['startDay']}"));
	}
	// Validate the end date
	if (isset($_POST['endDay']) && isset($_POST['endMonth']) && isset($_POST['endYear'])) { // All the elements of the date have been submitted
		$endDate = date('Y-n-j', strtotime("{$_POST['endYear']}-{$_POST['endMonth']}-{$_POST['endDay']}"));
	}
	// Check if the start date is not later than the end date
	if (strtotime($startDate) > strtotime($endDate)) {
		echo '<p class="error">Your start date cannot be later than your end date</p>';
		$problem = TRUE;
	}
	// Check if the gallery has been selected (or even if the functionality works):
	if (isset($_POST['galleryId'])) {
		$galleryId = $_POST['galleryId'];
	}
	// Check if the max depth has been entered
	if (isset($_POST['maxDepth'])) {
		$maxDepth = $_POST['maxDepth'];
		$units = $_POST['units'];
	}
	
	// Create the gallery if there was no problem
	if (!$problem) { // The form has been validated succesfully
		$diveTrip = new DiveTrip();
		if ($diveTrip->create()) {
			echo '<p class="error">Your dive trip has been succesfully saved</p>';
			$_POST = [];
		} else {
			echo '<p class="error">Something went wrong. Could not save your dive trip</p>';
		}
	}
} // End of form submission IF



// Create the form
?>
<!-- create the form to enter the data for the trip -->

<form action="trips.php?page=create" method="post">
<table class="profile">
<tr><td>Title of the trip: </td><td><input type="text" name="title" <?php if (isset($_POST['title'])) { echo ' value="'.$_POST['title'].'"';}?>/></td></tr>
<tr><td>Destination: </td><td><input type="text" name="destination" <?php if (isset($_POST['destination'])) { echo ' value="' . $_POST['destination'] . '"'; }?>/></td></tr>
<tr><td>Description: </td><td><textarea name="description" cols="30" rows="3"><?php if (isset($_POST['description'])) { echo "{$_POST['description']}"; }?></textarea></td></tr>
<!-- A section to enter the start date -->
<tr><td>Start date: </td><td><select name="startDay"><option selected disabled>DD</option>
<?php // Create the list of 31 days to select from
for ($i = 1; $i <= 31; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['startDay']) && ($_POST['startDay'] == $i)) { echo ' selected';}
	echo ">$i</option>";
}?>
</select>
<select name="startMonth"><option selected disabled>MM</option>
<?php // Show the list of 12 months to select from, take the lists from $months array from lists.php
foreach ($months as $key => $month) {
	echo "<option value=\"".($key+1)."\"";
	if (isset($_POST['startMonth']) && ($_POST['startMonth'] == ($key+1))) { echo ' selected';}
	echo ">$month</option>";
}?>
</select>
<select name="startYear"><option selected disabled>YYYY</option>
<?php // Show the list of years to select from
for ($i=date('Y'); $i>=2000; $i--) {
	echo "<option value=\"$i\"";
	if (isset($_POST['startYear']) && ($_POST['startYear'] == $i)) { echo ' selected';}
	echo ">$i</option>";
}?>
</select></td></tr>
<!-- A section to enter the end date -->
<tr><td>End date: </td><td><select name="endDay"><option selected disabled>DD</option>
<?php // Create the list of 31 days to select from
for ($i = 1; $i <= 31; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['endDay']) && ($_POST['endDay'] == $i)) { echo ' selected';}
	echo ">$i</option>";
}?>
</select>
<select name="endMonth"><option selected disabled>MM</option>
<?php // Show the list of 12 months to select from, take the lists from $months array from lists.php
foreach ($months as $key => $month) {
	echo "<option value=\"".($key+1)."\"";
	if (isset($_POST['endMonth']) && ($_POST['endMonth'] == ($key+1))) { echo ' selected';}
	echo ">$month</option>";
}?>
</select>
<select name="endYear"><option selected disabled>YYYY</option>
<?php // Show the list of years to select from
for ($i=date('Y'); $i>=2000; $i--) {
	echo "<option value=\"$i\"";
	if (isset($_POST['endYear']) && ($_POST['endYear'] == $i)) { echo ' selected';}
	echo ">$i</option>";
}?>
</select></td></tr>
<tr><td>Number of dives: </td><td><input type="number" name="noOfDives" <?php if (isset($_POST['noOfDives'])) { echo "value=\"{$_POST['noOfDives']}\""; }?>/></td></tr>
<?php // Create the list of user galleries' to be able to select from them
// Create an empty array:
$galleries = [];
// Create the MySQL query:
$q = 'SELECT galleryId, title FROM galleries WHERE authorId=:userId';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':userId' => $_SESSION['userId']));
if ($r) { // The query has been succesfull
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while ($result = $stmt->fetch()) {
		$galleries[] = $result;
	}
}
?>
<tr><td>Select the gallery: </td><td><select name="galleryId"><option selected disabled>Select your gallery</option>
<?php
if (count($galleries) > 0) {
	foreach ($galleries as $key => $gallery) {
		echo "<option value=\"{$gallery['galleryId']}\">{$gallery['title']}</option>";
	}
}?>
</select></td></tr>
<tr><td>Max depth reached: </td><td><select name="units"><option value="mt" <?php if (isset($_POST['units']) && ($_POST['units'] == 'mt')) { echo ' selected'; }?>>meters</option>
<option value="ft" <?php if (isset($_POST['units']) && ($_POST['units'] == 'ft')) { echo ' selected'; } ?>>feet</option>
<input type="number" name="maxDepth" <?php if (isset($_POST['maxDepth'])) { echo "value=\"{$_POST['maxDepth']}\""; }?> /></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Save the dive trip" /></td></tr>
</table>
</form>

</section>