<?php
/* The purpose of this script is to create a new dive plan. It will:
 * - display a HTML form, where the user will be able to inputthe key information about the plan
 * - the basic information will be: name, destination (selectable, country), duration
 * - handle the form, inlcuding it's validation
 * - create a new class object of the class DivePlan
 * - use the method of the DivePlan class object to save the new plan in the mysql database
 */
// Include the file with all the lists
include('includes/lists.php');

// Handle form submission
if (isset($_POST['submit'])) { // The form has been submitted

	// Define the $date variable, containing the month & the year of the planned trip
	$date=null;
	// Validate the form (make sure that all the fields have been filled)
	$problem=FALSE; // A variable that will check if any of the problems with the fields occured
	// Check each field
	if (empty($_POST['title'])) {
		$problem=TRUE;
		echo '<p class="error">Please enter the title for your dive trip</p>';
	}
	if (!isset($_POST['destination'])) {
		$problem=TRUE;
		echo '<p class="error">Please select your country of destination</p>';
	}
	if (!isset($_POST['month']) || !isset($_POST['year'])) {
		$problem=TRUE;
		echo '<p class="error">Please select the month and the year of your planned dive trip</p>';
	} else {
		$date=$_POST['month'].'-'.$_POST['year'];
	}

	// The form has been validated & can be urtherly handeled
	if (!$problem) {
		$plan = new DivePlan();
		if ($plan->create()) {
			echo '<p class="error">Your plan has been created</p>';
			$_POST=[];
		} else {
			echo '<p class="error">Something went wrong. Could not create your plan</p>';
		}
	}

}


?>


<section class="profile">
	<div class="title">
		<h3>Create your plan</h3>
		<p>Fill in the details of your planned dive trip. You will be able to add more elements after the plan is created</p>
	</div>
<div class="profile">

<form action="trips.php?page=createplan" method="post">
<table class="profile">
<tr><td>Title of the trip: </td><td><input type="text" name="title"<?php
if (isset($_POST['title'])) {
	echo "value=\"{$_POST['title']}\"";
}
?> /></td></tr>
<tr><td>Destination: </td><td><select name="destination"><option selected disabled>Select a country</option>
<?php
foreach ($countries as $key=>$country) {
	echo "<option value=\"$country\"";
	if (isset($_POST['destination'])) {
		if ($_POST['destination']==$country) {
			echo ' selected';
		}
	}
	echo ">$country</option>";
}
?>
<tr><td>Date: </td><td><select name="month"><option selected disabled>MM</option><?php
foreach ($months as $key=>$month) {
	echo '<option value="'.($key+1).'"';
	if (isset($_POST['month'])) {
		if ($_POST['month']==($key+1)) {
			echo ' selected';
		}
	}
	echo ">$month</option>";
}?></select><select name="year"><option selected disabled>YYYY</option><?php
for ($i=date('Y'); $i<=(date('Y')+10); $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['year'])) {
		if ($_POST['year']==$i) {
			echo ' selected';
		}
	}
	echo ">$i</option>";
}
?></select></td></tr>
<tr><td>Description: </td><td><textarea name="description" cols="40" rows="3"><?php
if (isset($_POST['description'])) {
	echo $_POST['description'];
}
?></textarea></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Create the plan" /></td></tr>
</table>
</form>
</div>
</section>