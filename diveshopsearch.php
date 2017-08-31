<?php
/* The purpose of this script will be to implement a search engine & display model for diveshops to be found. It will:
 * - 
 */

// Need the utilities file
require('includes/utilities.inc.php');

// Define the title & display the heading
define('TITLE', 'Dive shop search');
include('includes/header.inc.php');

// Check if the form has been submitted:
if (!isset($_POST['submit'])) {
	if (isset($_SESSION['search'])) {
		$_POST = $_SESSION['search'];
	}
}
?>
<!--Display the search form-->
<section class="search">
<div class="profile">
<form action="diveshopsearch.php" method="post">
<p>Search a diveshop by a keyword: <input type="text" name="keyWord" <?php
if (isset($_POST['keyWord'])) {
	echo " value=\"{$_POST['keyWord']}\"";
}
?>/><input type="submit" name="submit" value="search" /></p>
<p><input type="checkbox" name="DSDprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['DSDprice'])) {	
		echo ' checked';
	}
}
?> />DSD
<input type="checkbox" name="OWDprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['OWDprice'])) {	
		echo ' checked';
	}
}
?> />OWD
<input type="checkbox" name="AOWDprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['AOWDprice'])) {	
		echo ' checked';
	}
}
?> />AOWD
<input type="checkbox" name="RESCUEprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['RESCUEprice'])) {	
		echo ' checked';
	}
}
?> />Rescue
<input type="checkbox" name="DMprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['DMprice'])) {	
		echo ' checked';
	}
}
?> />DM
<input type="checkbox" name="IDCprice"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['IDCprice'])) {	
		echo ' checked';
	}
}
?> />IDC
<input type="checkbox" name="dayTrip"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['dayTrip'])) {	
		echo ' checked';
	}
}
?> />Day trip
<input type="checkbox" name="package"<?php 
if (isset($_POST['submit'])) {
	if (isset($_POST['package'])) {	
		echo ' checked';
	}
}
?> />Dive packages
</p>
<p><input type="radio" name="currency" value="local"<?php
if (isset($_POST['currency'])) {
	if ($_POST['currency']=='local') {
		echo ' checked';
	}
} else {
	echo ' checked';
}
?> />Local currency<input type="radio" name="currency" value="USD"<?php
if (isset($_POST['currency'])) {
	if ($_POST['currency']=='USD') {
		echo ' checked';
	}
}
?> />USD<input type="radio" name="currency" value="EUR"<?php
if (isset($_POST['currency'])) {
	if ($_POST['currency']=='EUR') {
		echo ' checked';
	}
}
?> />EUR</p>
</form>
</div>
</section>

<?php
if (isset($_POST['submit'])) {
	// Save $_POST data into $_SESSION variable
	$_SESSION['search'] = $_POST;
	// Define the array that will store the information of the searched dive shops
	$keyWord='%'.$_POST['keyWord'].'%';
	$shopData=[]; 
	// Create the query to retrieve data from the database:
	$q='SELECT * FROM diveshops WHERE (shopName LIKE :search) OR (shopCountry LIKE :search) OR (address LIKE :search)';
	$stmt=$pdo->prepare($q);
	$r=$stmt->execute(array(':search'=>$keyWord));
	if ($r) { // The query has been succesfully run
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		while ($result=$stmt->fetch()) {
			$shopData[]=$result;
		} // End of WHILE
	} // End of IF
	
	// Get the diveshops offer if the search returned any results
	if (!empty($shopData)) {
		// Define the array with the offer data:
		foreach ($shopData as $key=>$shop) {
			// Create the query to retrieve offer data from MySQL database:
			$q='SELECT * FROM diveshopoffer WHERE shopId=:shopId';
			$stmt=$pdo->prepare($q);
			$r=$stmt->execute(array('shopId'=>$shop['shopId']));
			if ($r) { // The query has been succesfully run
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				while ($result=$stmt->fetch()) {
					$shopData[$key]['offerData']=$result;
					switch ($_POST['currency']) {
						case 'local':
							$forex=1;
							break;
						case 'USD':
							// Run the query to get the USD exchange rate from mysql database:
							$q2='SELECT USDexRate FROM forex WHERE currency=:currency';
							$stmt2=$pdo->prepare($q2);
							$r2=$stmt2->execute(array(':currency'=>$shopData[$key]['offerData']['currency']));
							if ($r2) {
								$stmt2->setFetchMode(PDO::FETCH_ASSOC);
								if ($result2=$stmt2->fetch()) {
									$forex=$result2['USDexRate'];
								}
							}
							break;
						case 'EUR':
							// Run the query to get the USD exchange rate from mysql database:
							$q2='SELECT EURexRate FROM forex WHERE currency=:currency';
							$stmt2=$pdo->prepare($q2);
							$r2=$stmt2->execute(array(':currency'=>$shopData[$key]['offerData']['currency']));
							if ($r2) {
								$stmt2->setFetchMode(PDO::FETCH_ASSOC);
								if ($result2=$stmt2->fetch()) {
									$forex=$result2['EURexRate'];
								}
							}
							break;
					}// End of SWITCH
					// Turn each price from $shopData[$key]['offerData'] into the proper currency
					foreach ($shopData[$key]['offerData'] as $fieldName => $value) {
						if (strpos(strtolower($fieldName), 'price')!==false) {
							$shopData[$key]['offerData'][$fieldName] = round($shopData[$key]['offerData'][$fieldName] / $forex);
						} // End of IF
					} // End of foreach()
				}
			}
		}
		$_GET['page']='view';
	} // End of empty($shopData) IF
}// End of Form Submission IF


// Check if a specific page has been requested:
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'view':
			include('views/diveshopsearch/view.php');
			break;
		case 'show':
			include('views/diveshopsearch/show.php');
			break;
	} // End of switch
} // End of IF

// Need the footer
include('includes/footer.inc.php');
?>
