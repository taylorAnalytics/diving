<?php
/* This script will enable editing he details of a particular dive. It will:
 * - create the dive object & retrieve data from MySQL
 * - present the data in the form
 * - handle the form after it's submission (verification)
 * - update dive data in the MySQL database
 */
// Need the lists.php file
include('includes/lists.php');

// Create dive object & retrieve data for it
$dive = new Dive();
$dive->getData($_GET['diveId']);

// Prepare variables that will fill in the form initially
$diveDay = date('j', strtotime($dive->date));
$diveMonth = date('m', strtotime($dive->date));
$diveYear = date('Y', strtotime($dive->date));
$hourIn = ($dive->timeIn == null) ? null : intval(date('h', strtotime($dive->timeIn)));
$minutesIn = ($dive->timeIn == null) ? null : intval(date('i', strtotime($dive->timeIn)));
$hourOut = ($dive->timeOut == null) ? null : intval(date('h', strtotime($dive->timeOut)));
$minutesOut = ($dive->timeOut == null) ? null : intval(date('i', strtotime($dive->timeOut)));
$characteristics = explode(',', $dive->characteristics);
// Fill the $_POST['characteristics'] value with $characteristics
if (!isset($_POST['characteristics'])) {
	$_POST['characteristics'] = $characteristics;
}

// Validate the form
// Handle form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	


	$problem = FALSE;
	$diveDate = null;
	$timeIn = null;
	$timeOut = null;
	$o2level = null;
	$characteristics = null;
	$shopName = null;
	$shopId = null;
	// Check if the date has been submitted:
	if (!isset($_POST['day']) || !isset($_POST['month']) || !isset($_POST['year'])) { // One of the date elements has not been submitted
		echo '<p class="error">Please enter a full date of your dive</p>';
		$problem = TRUE;
	} else {
		$diveDate = date('Y-n-j', strtotime("{$_POST['year']}-{$_POST['month']}-{$_POST['day']}"));
	}
	if (empty($_POST['diveTime'])) { // The dive time has not been filled
		echo '<p class="error">Plase enter your dive time</p>';
		$problem = TRUE;
	}
	if (empty($_POST['maxDepth'])) { // The maximum depth has not been filled
		echo '<p class="error">Please enter the maximum depth of the dive</p>';
		$problem = TRUE;
	}
	if (empty($_POST['country'])) { // The country has not been selectd
		echo '<p class="error">Please select the country of your dive</p>';
		$problem = TRUE;
	}
	if (isset($_POST['hourIn']) && isset($_POST['minutesIn'])) { // The time in has been inputted
		$timeIn = date('G:i', strtotime("{$_POST['hourIn']}:{$_POST['minutesIn']}"));
	}
	if (isset($_POST['hourOut']) && isset($_POST['minutesOut'])) { // The time out has been inputted
		$timeOut = date('G:i', strtotime("{$_POST['hourOut']}:{$_POST['minutesOut']}"));
	}
	if (isset($timeIn) && isset($timeOut)) { // The time in & out have been specified
		if ((strtotime($timeOut) - strtotime($timeIn))/60 != $_POST['diveTime']) {
			echo '<p class="error">Your time in & out do not match your dive time</p>';
		}
	}
	if (isset($_POST['gas']) && ($_POST['gas'] == 'air')) {
		$_POST['o2Level'] = 21;
	}
	$o2level = $_POST['o2Level'];
	if (isset($_POST['characteristics'])) {
		$characteristics = implode(',', $_POST['characteristics']);
	}
	if (isset($_POST['diveshop'])) {
		if ($_POST['diveshop']==0) {
			$shopId=null;
			$shopName=null;
		} else {
			$shopId=$_POST['diveshop'];
			// Create the query to retrieve shopName from the database
			$q='SELECT shopName FROM diveshops WHERE shopId=:shopId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':shopId'=>$_POST['diveshop']));
			if ($r) {
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				if ($result=$stmt->fetch()) {
					$shopName=$result['shopName'];
				}
			}
		}
	}
	
	// The form has been validated & can be submitted
	if (!$problem) {
		if ($dive->update($dive->diveId)) {
			echo '<p class="error">Your dive has been updated</p>';
			// Create the list of all diveshops connected to this trip
			$shopList=[];
			$q='SELECT DISTINCT shopId FROM shopstrips WHERE tripId=:tripId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array(':tripId'=>$_GET['tripId']));
			if ($r) { // The query has been succesfully executed
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				while($result=$stmt->fetch()) {
					$shopList[]=$result;
				}
			}
			foreach ($shopList as $key=>$shop) {
				// Count the number of dives within the particular diveshop and insert it into shopstrips database (with a single query)
				$q2='UPDATE shopstrips SET noOfDives = (SELECT COUNT(diveId) FROM dives WHERE (shopId=:shopId) AND (tripId=:tripId)) WHERE (shopId=:shopId) AND (tripId=:tripId)';
				$stmt2=$pdo->prepare($q2);
				$r2=$stmt2->execute(array(':shopId'=>$shop['shopId'], ':tripId'=>$_GET['tripId']));
			}
			
		} else {
			echo '<p class="error">Something went wrong. Could not update your dive in the database</p>';
		}
	}
}

?>
<section class="profile">
<a href="trips.php?page=view&tripId=<?php echo $_GET['tripId']; ?>&diveId=<?php echo $_GET['diveId']; ?>" class="button-2">Back to view dive</a>
<form action="trips.php?page=edit&tripId=<?php echo $_GET['tripId']; ?>&diveId=<?php echo $_GET['diveId']; ?>" method="post">
<table class="profile">
<tr><td>Date of the dive: </td><td><select name="day"><option selected disabled>DD</option>
<?php // Create the list of 31 days to select from
for ($i = 1; $i <= 31; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['day']) && ($_POST['day'] == $i)) { 
		echo ' selected';
	} elseif ($diveDay == $i) {
		echo ' selected';
	}
	if ($i < 10) {
		echo ">0$i</option>";
	} else {
		echo ">$i</option>";
	}
}?>
</select>
<select name="month"><option selected disabled>MM</option>
<?php // Show the list of 12 months to select from, take the lists from $months array from lists.php
foreach ($months as $key => $month) {
	echo "<option value=\"".($key+1)."\"";
	if (isset($_POST['month']) && ($_POST['month'] == ($key+1))) { 
		echo ' selected';
	} elseif ($diveMonth == ($key+1)) {
		echo ' selected';
	}
	echo ">$month</option>";
}?>
</select>
<select name="year"><option selected disabled>YYYY</option>
<?php // Show the list of years to select from
for ($i=date('Y'); $i>=2000; $i--) {
	echo "<option value=\"$i\"";
	if (isset($_POST['year']) && ($_POST['year'] == $i)) { 
		echo ' selected';
	} elseif ($diveYear == $i) {
		echo ' selected';
	}
	echo ">$i</option>";
}?></td></tr>
<tr><td>Time in: </td><td><select name="hourIn"><option selected disabled>hh</option>
<?php // Create the list of hours
for ($i = 0; $i<=23; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['hourIn']) && $_POST['hourIn'] == $i) {
		echo ' selected';
	} elseif ($hourIn === $i) {
		echo ' selected';
	}
	if ($i < 10) {
		echo ">0$i</option>";
	} else {
		echo ">$i</option>";
	}
}
?></select>:<select name="minutesIn"><option selected disabled>mm</option>
<?php // Create the list of minutes
for ($i = 0; $i<=59; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['minutesIn']) && $_POST['minutesIn'] == $i) {
		echo ' selected';
	} elseif ($minutesIn === $i) {
		echo ' selected';
	}
	if ($i < 10) {
		echo ">0$i</option>";
	} else {
		echo ">$i</option>";
	}
}?></select></td></tr>
<tr><td>Time out: </td><td><select name="hourOut"><option selected disabled>hh</option>
<?php // Create the list of hours
for ($i = 0; $i<=23; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['hourOut']) && $_POST['hourOut'] == $i) {
		echo ' selected';
	} elseif ($hourOut === $i) {
		echo ' selected';
	}
	if ($i < 10) {
		echo ">0$i</option>";
	} else {
		echo ">$i</option>";
	}
}
?></select>:<select name="minutesOut"><option selected disabled>mm</option>
<?php // Create the list of minutes
for ($i = 0; $i<=59; $i++) {
	echo "<option value=\"$i\"";
	if (isset($_POST['minutesOut']) && $_POST['minutesOut'] == $i) {
		echo ' selected';
	} elseif ($minutesOut === $i) {
		echo ' selected';
	}
	if ($i < 10) {
		echo ">0$i</option>";
	} else {
		echo ">$i</option>";
	}
}?></select></td></tr>
<tr><td>Dive time: </td><td>minutes <input type="number" name="diveTime" size="10" <?php
if (isset($_POST['diveTime'])) { 
	echo "value=\"{$_POST['diveTime']}\"";
} else {
	echo "value=\"$dive->diveTime\"";
}
?>/></td></tr>
<tr><td>Max depth reached: </td><td><select name="units"><option value="mt" <?php
if (isset($_POST['units'])) {
	if ($_POST['units'] == 'mt') {
		echo ' selected';
	}
} elseif ($dive->units == 'mt') {
	echo ' selected';
}
?>>meters</option><option value="ft"<?php
if (isset($_POST['units'])) {
	if ($_POST['units'] == 'ft') {
		echo ' selected';
	}
} elseif ($dive->units == 'ft') {
	echo ' selected';
}
?>>feet</option>
<input type="number" name="maxDepth" size="10" <?php 
if (isset($_POST['maxDepth'])) { 
	echo "value=\"{$_POST['maxDepth']}\""; 
} else {
	echo "value=\"$dive->maxDepth\"";
}
?>/></td></tr>
<tr><td>Gas: </td><td><input id="air" type="radio" name="gas" value="air" <?php 
if (isset($_POST['gas'])) {
	if ($_POST['gas'] == 'air') {
		echo ' checked';
	}
} elseif ($dive->gas=='air') {
	echo ' checked';
} 
?> onclick="eanCheck();"/>Air
<input type="radio" name="gas" value="ean" <?php 
if (isset($_POST['gas'])) {
	if ($_POST['gas'] == 'ean'){
		echo ' checked';
	}
} elseif ($dive->gas=='ean') {
	echo ' checked';
}
?> onclick="eanCheck();">EAN 
<p id="o2level">O2 level:<input type="number" name="o2Level"  min="0" max="100" value=
<?php 
if (isset($_POST['gas'])) {
	if ($_POST['gas'] == 'air') {
		echo '"21" />%';
		echo ' <script type="text/javascript">eanCheck();</script>';
	} elseif ($_POST['gas'] == 'ean') {
		echo "\"{$_POST['o2Level']}\" />%";
		echo ' <script type="text/javascript">eanCheck();</script>';
	}
} elseif ($dive->gas=='ean') {
	echo "\"$dive->o2Level\" />%";
	echo ' <script type="text/javascript">eanCheck();</script>';
} else {
	echo '"21" />%';
}
?></p></td></tr>
<?php
// Check if any dive shops have been added to the divetrip & create a selectable list of them if so
// Open the list
echo '<tr><td>Dive Shop: </td><td><select name="diveshop"><option value="0" selected>None</option>';
// Create the query to check if there are any trip-shops connections
$q='SELECT * FROM shopstrips WHERE tripId=:tripId';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array('tripId'=>$_GET['tripId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while($result=$stmt->fetch()) {
		// Create the query to retrieve shopName from the database
		$q2='SELECT shopName FROM diveshops WHERE shopId=:shopId';
		$stmt2=$pdo->prepare($q2);
		$r2=$stmt2->execute(array(':shopId'=>$result['shopId']));
		if ($r2) {
			$stmt2->setFetchMode(PDO::FETCH_ASSOC);
			if ($result2=$stmt2->fetch()) {
				echo '<option value="'.$result['shopId'].'"';
				if (isset($_POST['diveshop'])) {
					if ($_POST['diveshop']==$result['shopId']) {
						echo ' selected';	
					}
				} elseif ($dive->shopId==$result['shopId']) {
					echo ' selected';
				} 
				echo '>'.$result2['shopName'].'</option>';
			}
		}
	}
}
echo '</select></td></tr>';
?>

<tr><td>Country: </td><td><select name="country"><option selected disabled>Select a country</option>
<?php // Create a selectable list of countries
foreach ($countries as $country) {
	echo "<option value=\"$country\"";
	if (isset($_POST['country'])) {
		if ($country == $_POST['country']) {
			echo ' selected';
		}
	} elseif ($dive->country == $country) {
		echo ' selected';
	}
	echo ">$country</option>";
}?></select></td></tr>
<tr><td>Dive site name: </td><td><input type="text" name="diveSite" size="30" <?php
if (isset($_POST['diveSite'])) { 
	echo "value=\"{$_POST['diveSite']}\"";
} else {
	echo "value=\"$dive->diveSite\"";
} 
?>/></td></tr>
<tr><td>Marine life spotted: </td><td><input type="text" name="marineLife" size="40" <?php
if (isset($_POST['marineLife'])) {
	echo "value=\"{$_POST['marineLife']}\"";
} else {
	echo "value=\"$dive->marineLife\"";
}
?>/></td></tr>
<tr><td>Description: </td><td><textarea name="description" cols="40" rows="5"><?php
if (isset($_POST['description'])) {
	echo $_POST['description'];
} else {
	echo $dive->description;
}
?></textarea></td></tr>
<tr><td>Dive characteristics: </td><td>
<input type="checkbox" name="characteristics[]" multiple="multiple" value="shore" <?php if (isset($_POST['characteristics']) && in_array('shore', $_POST['characteristics'])) { echo ' checked'; } ?>/>Shore
<input type="checkbox" name="characteristics[]" multiple="multiple" value="boat" <?php if (isset($_POST['characteristics']) && in_array('boat', $_POST['characteristics'])) { echo ' checked'; } ?> />Boat
<input type="checkbox" name="characteristics[]" multiple="multiple" value="deep" <?php if (isset($_POST['characteristics']) && in_array('deep', $_POST['characteristics'])) { echo ' checked'; } ?> />Deep
<input type="checkbox" name="characteristics[]" multiple="multiple" value="night" <?php if (isset($_POST['characteristics']) && in_array('night', $_POST['characteristics'])) { echo ' checked'; } ?> />Night<br>
<input type="checkbox" name="characteristics[]" multiple="multiple" value="wall" <?php if (isset($_POST['characteristics']) && in_array('wall', $_POST['characteristics'])) { echo ' checked'; } ?> />Wall
<input type="checkbox" name="characteristics[]" multiple="multiple" value="wreck" <?php if (isset($_POST['characteristics']) && in_array('wreck', $_POST['characteristics'])) { echo ' checked'; } ?> />Wreck
<input type="checkbox" name="characteristics[]" multiple="multiple" value="cavern" <?php if (isset($_POST['characteristics']) && in_array('cavern', $_POST['characteristics'])) { echo ' checked'; } ?> />Cavern
<input type="checkbox" name="characteristics[]" multiple="multiple" value="cave" <?php if (isset($_POST['characteristics']) && in_array('cave', $_POST['characteristics'])) { echo ' checked'; } ?> />Cave<br>
<input type="checkbox" name="characteristics[]" multiple="multiple" value="reef" <?php if (isset($_POST['characteristics']) && in_array('reef', $_POST['characteristics'])) { echo ' checked'; } ?> />Reef
<input type="checkbox" name="characteristics[]" multiple="multiple" value="drift" <?php if (isset($_POST['characteristics']) && in_array('drift', $_POST['characteristics'])) { echo ' checked'; } ?> />Drift
<input type="checkbox" name="characteristics[]" multiple="multiple" value="drysuit" <?php if (isset($_POST['characteristics']) && in_array('drysuit', $_POST['characteristics'])) { echo ' checked'; } ?> />Drysuit
<input type="checkbox" name="characteristics[]" multiple="multiple" value="ice" <?php if (isset($_POST['characteristics']) && in_array('ice', $_POST['characteristics'])) { echo ' checked'; } ?> />Ice<br>
<input type="checkbox" name="characteristics[]" multiple="multiple" value="sidemount" <?php if (isset($_POST['characteristics']) && in_array('sidemount', $_POST['characteristics'])) { echo ' checked'; } ?> />Sidemount
<input type="checkbox" name="characteristics[]" multiple="multiple" value="solo" <?php if (isset($_POST['characteristics']) && in_array('solo', $_POST['characteristics'])) { echo ' checked'; } ?> />Solo
</td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Save dive info" /></td></tr>
</table>
</form>
</section>