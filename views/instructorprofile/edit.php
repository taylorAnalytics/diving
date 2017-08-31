<?php
/* The purpose of this script is to enable the editing of instructor's profile. It will:
 * - get the $instructor object from $_SESSION variable
 * - use the object method to get user's data from MySQL database
 * - show html form that will collect information
 * - fill in the form initially with the data from mysql database
 * - handle the form submission
 * - use the objects method to update the data in the mysql database once the editing is finished
 */
// Get the instructor object from $_SESSION
$instructor=$_SESSION['instructor'];
$instructor->getUserData();
// Include the file with the lists (countries, etc.)
include('includes/lists.php');
$dayOfBirth=date('j', strtotime($instructor->userData['dateOfBirth']));
$monthOfBirth=date('m', strtotime($instructor->userData['dateOfBirth']));
$yearOfBirth=date('Y', strtotime($instructor->userData['dateOfBirth']));

// Check if the form has been submitted
if (isset($_POST['submit'])) { // The form has been submitted and needs to be handled
	$problem = FALSE;
	if (empty($_POST['firstName'])) {
		$problem = TRUE;
		echo '<p class="error">Your first name cannot be left empty</p>';
	}
	if (empty($_POST['lastName'])) {
		$problem = TRUE;
		echo '<p class="error">You last name canno be left empty</p>';
	}
	if (empty($_POST['username'])) {
		$problem = TRUE;
		echo '<p class="error">Your username cannot be left empty</p>';
	}
	if (isset($_POST['dayOfBirth']) && isset($_POST['monthOfBirth']) && isset($_POST['yearOfBirth'])) {
		$dateOfBirth=date('Y-m-j', strtotime($_POST['yearOfBirth'].'-'.$_POST['monthOfBirth'].'-'.$_POST['dayOfBirth']));
	}
	if ($_POST['teachingSince'] < $_POST['divingSince']) {
		$problem = TRUE;
		echo '<p class="error">You cannot have started teaching before you started diving. Fix the dates please.</p>';
	}
	if (isset($_POST['specialties'])) {
		if (count($_POST['specialties'])==1) {
			$specialties = $_POST['specialties'][0];
		} else {
			$specialties = implode(',',$_POST['specialties']);
		}
	} else {
		$specialties = null;
	}
	// Check if there was any error reported dring the form validation
	if (!$problem) {
		if ($instructor->update($_SESSION['userId'])) {
			$instructor->getUserData();
			$dayOfBirth=date('j', strtotime($instructor->userData['dateOfBirth']));
			$monthOfBirth=date('m', strtotime($instructor->userData['dateOfBirth']));
			$yearOfBirth=date('Y', strtotime($instructor->userData['dateOfBirth']));
			echo '<p class="error">Great, no problem, you have been updated</p>';
		} else {
		echo '<p class="error">Shit, something went wrong. Could no update your information</p>';
		} // End of update IF
	} // End of !$problem IF
} // End of validation IF

?>

<section class="profile">
<form action="instructorprofile.php?page=edit" method="post">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Personal information: </td><tr>
<tr><td>First name: </td><td><input type="text" name="firstName" size="30" value="<?php
if (isset($_POST['firstName'])) {
	echo $_POST['firstName'];
} else {
	echo $instructor->userData['firstName'];
}
?>"/></td></tr>
<tr><td>Last name: </td><td><input type="text" name="lastName" size="30" value="<?php
if (isset($_POST['lastName'])) {
	echo $_POST['lastName'];
} else {
	echo $instructor->userData['lastName'];
}
?>"/></td></tr>
<tr><td>Username: </td><td><input type="text" name="username" size="30" value="<?php
if (isset($_POST['username'])) {
	echo $_POST['username'];
} else {
	echo $instructor->userName;
}
?>"/></td></tr>
<tr><td>Country of origin: </td><td><select name="countryOfOrigin">
<?php
foreach ($countries as $country) {
	echo "<option value=\"$country\"";
	if (isset($_POST['countryOfOrigin'])) {
		if ($_POST['countryOfOrigin'] == $country) {
			echo ' selected';
		}
	} elseif ($country == $instructor->userData['countryOfOrigin']) {
		echo ' selected';
	}
	echo ">$country</option>";
}
?></select></td></tr>
<tr><td>Date of birth: </td><td><select name="dayOfBirth"><?php
for ($i = 1; $i<=31; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['dayOfBirth'])) {
		if ($_POST['dayOfBirth'] == $i) {
			echo ' selected';
		}
	} elseif ($dayOfBirth == $i) {
		echo ' selected';
	}
	echo ">$i</option>";
}?></select>
<select name="monthOfBirth"><?php
foreach ($months as $key => $month) {
	echo "<option value=\"".($key+1)."\"";
	if (isset($_POST['monthOfBirth'])) {
		if ($_POST['monthOfBirth']==($key+1)) {
			echo ' selected';
		}
	} elseif ($monthOfBirth == ($key + 1)) {
		echo ' selected';
	}
	echo ">$month</option>";
}?></select>
<select name="yearOfBirth"><?php
for ($i=date('Y'); $i >= 1950; $i--) {
	echo "<option value=\"$i\"";
	if (isset($_POST['yearOfBirth'])) {
		if ($_POST['yearOfBirth'] == $i) {
			echo ' selected';
		}
	} elseif ($yearOfBirth == $i) {
		echo ' selected';
	}
	echo ">$i</option>";
}
?></select>
<tr><td colspan="2" class="centered shadow">Dive experience</td></tr>
<tr><td>Diving since: </td><td><select name="divingSince"><?php
for ($i=date('Y'); $i>=1980; $i--) {
	echo '<option value="'.$i.'"';
	if (isset($_POST['divingSince'])) {
		if ($_POST['divingSince'] == $i) {
			echo ' selected';
		}
	} elseif ($instructor->userData['divingSince'] == $i) {
		echo ' selected';
	}
	echo '>'.$i.'</option>';
}
?></select></td></tr>
<tr><td>Number of dives: </td><td><input type="number" name="noOfDives" <?php
if (isset($_POST['noOfDives'])) {
	echo "value=\"{$_POST['noOfDives']}\" />";
} else {
	echo "value=\"".$instructor->userData['noOfDives']."\" />";
}
?> </td></tr>
<tr><td colspan="2" class="centered shadow">Instructor experience</td></tr>
<tr><td>Training organization: </td><td><select name="trainOrg"><?php
foreach ($certAgencies as $agency) {
	echo '<option value="'.$agency.'"';
	if (isset($_POST['trainOrg'])) {
		if ($_POST['trainOrg'] == $agency) {
			echo ' selected';
		}
	} elseif ($instructor->userData['trainOrg'] == $agency) {
		echo ' selected';
	}
	echo '>'.$agency.'</option>';
}
?></select></td></tr>
<tr><td>Instructor level: </td><td><select name="instrLevel"><?php
foreach ($instLevels as $level) {
	echo '<option value="'.$level.'"';
	if (isset($_POST['instrLevel'])) {
		if ($_POST['instrLevel']==$level) {
			echo ' selected';
		}
	} elseif ($instructor->userData['instrLevel']==$level) {
		echo ' selected';
	}
	echo '>'.$level.'</option>';
}
?></select></td></tr>
<tr><td>Number of certifications: </td><td><input type="number" name="noOfCerts"<?php
if (isset($_POST['noOfCerts'])) {
	echo 'value="'.$_POST['noOfCerts'].'">';
} else {
	echo 'value="'.$instructor->userData['noOfCerts'].'">';
}
?></td></tr>
<tr><td>Teaching since: </td><td><select name="teachingSince"><?php
for ($i=date('Y'); $i>=1980; $i--) {
	echo "<option value=\"$i\"";
	if (isset($_POST['teachingSince'])) {
		if ($_POST['teachingSince']==$i) {
			echo ' selected';
		}
	} elseif ($instructor->userData['teachingSince']==$i) {
		echo ' selected';
	}
	echo ">$i</option>";
}
?></select></td></tr>
<tr><td>Country of residence: </td><td><select name="countryOfResidence"><?php
foreach ($countries as $country) {
	echo "<option value=\"$country\"";
	if (isset($_POST['countryOfResidence'])) {
		if ($_POST['countryOfResidence']==$country) {
			echo ' selected';
		}
	} elseif ($instructor->userData['countryOfResidence']==$country) {
		echo ' selected';
	}
	echo ">$country</option>";
}
?></select></td></tr>
<tr><td>Instructor specialties: </td><td><?php
foreach ($specs as $specialty) {
	echo "<input type=\"checkbox\" name=\"specialties[]\" value=\"$specialty\"";
	if (isset($_POST['specialties'])) {
		if (in_array($specialty, $_POST['specialties'])) {
			echo ' checked';
		}
	}
	if (in_array($specialty, explode(',', $instructor->userData['specialties']))) {
		echo ' checked';
	}
	echo " />$specialty<br>";
}
?></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Update profile information"/></td></tr>
</table>
</form>
</section>