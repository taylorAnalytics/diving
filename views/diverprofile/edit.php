<?php
$diver = $_SESSION['diver'];
$diver->getUserData();
include('includes/lists.php');

if (isset($_POST['submit'])) {
	$problem = FALSE;
	if (empty($_POST['firstName'])) {
		$problem = TRUE;
		echo '<p class="error">Your first name cannot be left empty</p>';
	}
	if (empty($_POST['lastName'])) {
		$problem = TRUE;
		echo '<p class="error">You last name canno be left empty</p>';
	}
	if (empty($_POST['userName'])) {
		$problem = TRUE;
		echo '<p class="error">Your username cannot be left empty</p>';
	}
	if (empty($_POST['email'])) {
		$problem = TRUE;
		echo '<p class="error">Your email cannot be left empty</p>';
	}
	if (!$problem) {
		if ($diver->updateUserData()) {
			echo '<p class="error">Your data has been updated</p>';
			$diver->getUserData();
		} else {
			echo '<p class="error">Something went wrong. Could not update your data</p>';
		} // End of update IF
	} // End of !$problem IF
}

?>

<section class="profile">
<form action="diverprofile.php?page=edit" method="post">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Personal information: </td><tr>
<tr><td>First name: </td><td><input type="text" name="firstName" size="30" value="<?php echo $diver->userData['firstName']; ?>"/></td></tr>
<tr><td>Last name: </td><td><input type="text" name="lastName" size="30" value="<?php echo $diver->userData['lastName']; ?>"/></td></tr>
<tr><td>Username: </td><td><input type="text" name="userName" size="30" value="<?php echo $diver->userData['userName']; ?>"/></td></tr>
<tr><td>Email address: </td><td><input type="email" name="email" size="30" value="<?php echo $diver->userData['email']; ?>"/></td></tr>
<tr><td>Country of origin: </td><td><select name="countryOfOrigin">
<?php
foreach ($countries as $country) {
	echo "<option value=\"$country\"";
	if ($country == $diver->userData['countryOfOrigin']) echo ' selected';
	echo ">$country</option>";
}
?></select></td></tr>
<tr><td colspan="2" class="centered shadow">Dive experience</td></tr>
<tr><td>Certification agency: </td><td><select name="certAgency">
<?php 
foreach ($certAgencies as $agency) {
	echo "<option value=\"$agency\"";
	if ($agency == $diver->userData['certAgency']) echo ' selected';
	echo ">$agency</option>";
}
?></select></td></tr>
<tr><td>Certification level: </td><td><select name="certLevel">
<?php
foreach ($certLevels as $level) {
	echo "<option value=\"$level\"";
	if ($level == $diver->userData['certLevel']) echo ' selected';
	echo ">$level</option>";
}
?></select></td></tr>
<tr><td>Certification card number: </td><td><input type="text" name ="certCardId" value="<?php echo $diver->userData['certCardId']; ?>"/></td></tr>
<tr><td>Diving since: </td><td><select name="divingSince">
<?php 
for ($i = 2016; $i >= 1980; $i--) {
	echo "<option value=\"$i\"";
	if ($i == $diver->userData['divingSince']) echo ' selected';
	echo ">$i</option>";
}
?></select></td></tr>
<tr><td>Number of dives: </td><td><input type="number" name="noOfDives" value="<?php echo $diver->userData['noOfDives']; ?>"></td></tr>
<tr><td colspan="2" class="centered shadow">Diver favorites</td></tr>
<tr><td>Your favorite dive site: </td><td><input type="text" name="favoriteSite" value="<?php echo $diver->userData['favoriteSite'];?>"></td></tr>
<tr><td>Your best dive experience: </td><td><textarea name="bestExperience" cols="30" rows="5"><?php echo $diver->userData['bestExperience'];?></textarea></td></tr>
<tr><td>Your dive interests: </td><td>
<?php
foreach ($interests as $v) {
	echo "<input type=\"checkbox\" name=\"diveInterests[]\" value=\"$v\"";
	if (in_array($v, explode(",", $diver->userData['diveInterests']))) echo ' checked';
	echo ">$v<br>";
}
?>
</td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Update profile information"/></td></tr>
</form>
</table>
</section>
</div>