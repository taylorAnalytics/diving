<?php
/* The purpose of this script is to enable the editing of shop's profile. It will:
 * - get the $shop object from $_SESSION variable
 * - use the object method to get user's data from MySQL database
 * - show html form that will collect information
 * - fill in the form initially with the data from mysql database
 * - handle the form submission
 * - use the objects method to update the data in the mysql database once the editing is finished
 */

// Need the file twith all the lists:
include('includes/lists.php');

// Run the form validation
if (isset($_POST['submit'])) {
	$problem=FALSE;
	if (empty($_POST['shopName'])) {
		$problem=TRUE;
		echo '<p class="error">You can\'t leave the dive shop name empty</p>';
	}
	if (empty($_POST['userName'])) {
		$problem=TRUE;
		echo '<p class="error">You can\'t leave the username empty</p>';
	}
	if (empty($_POST['website'])) {
		$problem=TRUE;
		echo '<p class="error">You can\'t leave the website empty</p>';
	}
	// Run the update method of $shop object if there was no problem
	if (!$problem) {
		if($shop->update($_SESSION['userId'])) {
			echo '<p class="error">Your dive shop profile has been updated</p>';
		} else {
			echo '<p class="error">Something went wrong. We couldn\'t update your profile</p>';
		}
	}
}

?>
<section class="profile">
<form action="diveshopprofile.php?page=edit" method="post">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Dive shop's information</td></tr>
<tr><td>Dive shop name: </td><td><input type="text" name="shopName" value="<?php
if (isset($_POST['shopName'])) {
	echo $_POST['shopName'];
} else {
	echo $shop->userData['shopName'];
}
?>"/></td></tr>
<tr><td>Username: </td><td><input type="text" name="userName" value="<?php
if (isset($_POST['userName'])) {
	echo $_POST['userName'];
} else {
	echo $shop->userData['userName'];
}
?>"/></td></tr>
<tr><td>Website: </td><td><input type="url" name="website" size="50" value="<?php
if (isset($_POST['website'])) {
	echo $_POST['website'];
} else {
	echo $shop->userData['website'];
}
?>"/></td></tr>
<tr><td>Dive shop country: </td><td><select name="country">
<?php
foreach ($countries as $country) {
	echo "<option value=\"$country\"";
	if (isset($_POST['country'])) {
		if($_POST['country']==$country) {
			echo ' selected';
		}
	} elseif ($shop->userData['shopCountry']==$country) {
		echo ' selected';
	}
	echo ">$country</option>";
} // End of foreach
?></select></td></tr>
<tr><td>Address: </td><td><textarea name="address" cols="50" rows="3"><?php
if (isset($_POST['address'])) {
	echo $_POST['address'];
} else {
	echo $shop->userData['address'];
}
?></textarea></td></tr>
<tr><td>Zip-code: </td><td><input type="text" name="zipCode" value="<?php
if (isset($_POST['zipCode'])) {
	echo $_POST['zipCode'];
} else {
	echo $shop->userData['zipCode'];
}
?>" /></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Update profile information" /></td></tr>
</table>
</form>