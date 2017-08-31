<?php
/* This script will present all the information about the plan and allow adding new elements. It will:
 * - retrieve plan information from the mysql database
 * - create a new DivePlan object and fill it with information using the getData() method of the object
 * - present the informtation in the page
 * - include some buttons that will redirect / do something to add diveshops, galleries, articles
 * - update whenever something is added
 */

// Create the new DivePlan object & retrieve all it's data
$plan=new DivePlan();
if ($plan->getData($_GET['planId'])) {
	
}

?>
<section class="profile">
<div class="trip">
<h2><?php echo $plan->title; ?></h2>
<h4>Destination: <?php echo $plan->destination; ?></h4>
<p>Date: <?php echo $plan->date; ?></p>
<p><strong>Description:</strong><br>
<?php echo nl2br($plan->description); ?></p>
<br>

<!--Show the list of diveshops associated with this dive trip-->
<h4>Dive Shops</h4>
<?php
// Create the array with the dive shops that will be filled with the dive shops from the database
$diveshops=[];
if (!empty($plan->diveShops)) {
	$diveshops=explode(',', $plan->diveShops);
	// Open up the table
	echo '<table class="result">
		<tr><th>Dive shop name</th><th>Dive shop profile</th><th>Website</th><th>Delete</th></tr>';

	// Retrieve the data about each of the diveshops from the diveshops mysql database
	foreach ($diveshops as $diveshop) {
		// Create the query
		$q='SELECT * FROM diveshops WHERE shopId=:shopId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':shopId'=>$diveshop));
		if ($r) { // The query has been run succesfully
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			while ($result=$stmt->fetch()) {
				// for each dive shop from the list print the table row with the key information
				echo "<tr><td>{$result['shopName']}</td>
				<td><a href=\"showdiveshop.php?email={$result['email']}&page=show\">View</a></td>
				<td><a href=\"{$result['website']}\" target=\"_blank\">".substr($result['website'],7)."</a></td>
				<td><a href=\"trips.php?page=delete&shopId={$result['shopId']}&planId={$plan->planId}\">Delete</a></td></tr>";
			}
		}
	}
	// Close the table
	echo '</table>';
}?>
<p><a class="button-2" href="diveshopsearch.php">Add a dive shop</a></p>
<!--Show the list of all galleries and enable adding a new one-->
<h4>Galleries</h4>
<?php
// Create the list of the galleries from the mysql database
// Create the array of the galleries
$galleries=[];
if (!empty($plan->galleries)) {
	$galleries=explode(',', $plan->galleries);
	// Retrieve the information about each of the galleries from the mysql database
	foreach ($galleries as $key=>$gallery) {
		// Create the query
		$q='SELECT * FROM galleries WHERE galleryId=:galleryId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array('galleryId'=>$gallery));
		if ($r) { // The query has been succesfully run
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
			while ($gallery=$stmt->fetch()) { // The data has been retrieved
				echo $gallery->galleryId;
			}
		}
	}
}

?>
<p><a class="button-3" href="gallery.php?page=public">Add a gallery</a></p>

</div>
</section>