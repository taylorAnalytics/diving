<?php
/* The purpose of this script is to create & edit the dive shop prife offer. It will:
 * - get the current offer from the mysql database
 * - show the form, that will enable putting in all the information
 * - fill up the form with the data from MySQL database 
 * - validate & handle the form
 * - update data within the mysql database using the $shop object method (or should it be a separate class of Offer?)
 */

// Need the lists.php file
include('includes/lists.php');

// retrieve dive shop data & price offer using the methods of DiveShop class object
$shop->getUserData();
$shop->getOffer($shop->userId);

// Check if the form has been submitted:
if (isset($_POST['submit'])) {
	// Calcualte the average price per day trip dive:
	if (isset($_POST['daytripDives']) && isset($_POST['daytripPrice']) && ($_POST['daytripDives'] > 0)) {
		$daytripPricePerDive = round($_POST['daytripPrice'] / $_POST['daytripDives']);
	} else {
		$daytripPricePerDive = null;
	}
	// Calculate the average price per package dive
	if (isset($_POST['packageDives']) && isset($_POST['packagePrice']) && ($_POST['packageDives']>0)) {
		$packagePricePerDive = round($_POST['packagePrice'] / $_POST['packageDives']);
	} else {
		$packagePricePerDive = null;
	}
	// Check if the offer has already been created for this dive shop
	if (empty($shop->offerData)) {
		if ($shop->createOffer($_SESSION['userId'])) {
			echo '<p class="error">Your dive shop\'s offer has been created</p>';
		} else {
			echo '<p class="error">Something went wrong. Could not create your offer</p>';
		}
	} else {
		if ($shop->updateOffer($_SESSION['userId'])) {
			echo '<p class="error">Your dive shop\'s offer has been updated</p>';
		} else {
			echo '<p class="error">Something went wrong. Could not update your offer</p>';
		}
	}
}
?>

<section class="profile">
<form action="diveshopprofile.php?page=editoffer" method="post">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Course prices</td></tr>
<tr><td>Price currency: </td><td><select name="currency"><?php
foreach ($currencies as $key=>$currency) {
	echo "<option value=\"$key\"";
	if (isset($_POST['currency'])) {
		if($_POST['currency']==$key) {
			echo ' selected';
		}
	} elseif (isset($shop->offerData['currency']) && ($shop->offerData['currency']==$key)) {
		echo ' selected';
	}
	echo ">$currency</option>";
}

?></select></td></tr>
<tr><td>DSD price: </td><td><input type="number" name="DSDprice" <?php
if (isset($_POST['DSDprice'])) {
	echo "value=\"{$_POST['DSDprice']}\"";
} elseif (isset($shop->offerData['DSDprice'])) {
	echo "value=\"{$shop->offerData['DSDprice']}\"";
}
echo '/>';
?></td></tr>
<tr><td>OWD price: </td><td><input type="number" name="OWDprice" <?php
if (isset($_POST['OWDprice'])) {
	echo "value=\"{$_POST['OWDprice']}\"";
} elseif (isset($shop->offerData['OWDprice'])) {
	echo "value=\"{$shop->offerData['OWDprice']}\"";
}
echo '/>'
?></td></tr>
<tr><td>AOWD price: </td><td><input type="number" name="AOWDprice" <?php
if (isset($_POST['AOWDprice'])) {
	echo "value=\"{$_POST['AOWDprice']}\"";
} elseif (isset($shop->offerData['AOWDprice'])) {
	echo "value=\"{$shop->offerData['AOWDprice']}\"";
}
echo '/>';
?></td></tr>
<tr><td>Rescue price: </td><td><input type="number" name="RESCUEprice" <?php
if (isset($_POST['RESCUEprice'])) {
	echo "value=\"{$_POST['RESCUEprice']}\"";
} elseif (isset($shop->offerData['RESCUEprice'])) {
	echo "value=\"{$shop->offerData['RESCUEprice']}\"";
}
echo '/>';
?></td></tr>
<tr><td>DM price: </td><td><input type="number" name="DMprice" <?php
if (isset($_POST['DMprice'])) {
	echo "value=\"{$_POST['DMprice']}\"";
} elseif (isset($shop->offerData['DMprice'])) {
	echo "value=\"{$shop->offerData['DMprice']}\"";
}
echo '/>';
?></td></tr>
<tr><td>IDC price: </td><td><input type="number" name="IDCprice" <?php
if (isset($_POST['IDCprice'])) {
	echo "value=\"{$_POST['IDCprice']}\"";
} elseif (isset($shop->offerData['IDCprice'])) {
	echo "value=\"{$shop->offerData['IDCprice']}\"";
}
echo '/>';
?></td></tr>
<tr><td>Manuals included: </td><td>
<input type="radio" name="manual" value="1"<?php
if (isset($_POST['manual'])) {
	if ($_POST['manual']==1) {
		echo ' checked';
	}
} elseif (isset($shop->offerData['manual'])) {
	if ($shop->offerData['manual']==1) {
	echo ' checked';
	}
} else {
	echo ' checked';
}
?> />Yes
<input type="radio" name="manual" value="0" <?php
if (isset($_POST['manual'])) {
	if ($_POST['manual']==0) {
		echo ' checked';
	}
} elseif (isset($shop->offerData['manual'])) {
	if ($shop->offerData['manual']==0) {
	echo ' checked';
	}
}
?> />No
</td></tr>
<tr><td>Accommodation included: </td><td>
<input type="radio" name="accommodation" value="1"<?php
if (isset($_POST['accommodation'])) {
	if ($_POST['accommodation']==1) {
		echo ' checked';
	}
} elseif (isset($shop->offerData['accommodation']) && ($shop->offerData['accommodation']==1)) {
	echo ' checked';
}
?> />Yes
<input type="radio" name="accommodation" value="0" <?php
if (isset($_POST['accommodation'])) {
	if ($_POST['accommodation']==0) {
		echo ' checked';
	}
} elseif (isset($shop->offerData['accommodation'])) {
	if ($shop->offerData['accommodation']==0) {
	echo ' checked';
	}
} else {
	echo ' checked';
}
?> />No
</td></tr>
<tr><td colspan="2" class="centered shadow">Diving prices</td></tr>
<tr><td># of dives per day trip: </td><td><input type="number" name="daytripDives" <?php
if (isset($_POST['daytripDives'])) {
	echo "value=\"{$_POST['daytripDives']}\" ";
} elseif (isset($shop->offerData['daytripDives'])) {
	echo "value=\"{$shop->offerData['daytripDives']}\"";
}
?>/></td></tr>
<tr><td>Price of a day trip: </td><td><input type="number" name="daytripPrice" <?php
if (isset($_POST['daytripPrice'])) {
	echo "value=\"{$_POST['daytripPrice']}\" ";
} elseif (isset($shop->offerData['daytripPrice'])) {
	echo "value=\"{$shop->offerData['daytripPrice']}\"";
}
echo '/>';
?>
<tr><td># of dives in a package: </td><td><input type="number" name="packageDives" <?php
if (isset($_POST['packageDives'])) {
	echo "value=\"{$_POST['packageDives']}\" ";
} elseif (isset($shop->offerData['packageDives'])) {
	echo "value=\"{$shop->offerData['packageDives']}\"";
}
?>/></td></tr>
<tr><td>Price of a package: </td><td><input type="number" name="packagePrice"<?php
if (isset($_POST['packagePrice'])) {
	echo "value=\"{$_POST['packagePrice']}\" ";
} elseif (isset($shop->offerData['packagePrice'])) {
	echo "value=\"{$shop->offerData['packagePrice']}\"";
}
?>/></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Save your offer"></td></tr>

</table>
</form>
</section>