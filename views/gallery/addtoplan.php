<?php
/* The purpose of this script is to add a gallery to a dive plan. It will:
 * - have the galleryId in the $_GET[] variable
 * - retrieve the list of all user's dive plans from mysql database
 * - show a form that will enable selecting one dive plan
 * - validate & handle the form
 * - run the query to add the gallery to mysql field of the plan
 */

// Create the query to retrieve all user's plans from mysql database:
$plans=[];
$q='SELECT planId, title FROM plans WHERE userId=:userId ORDER BY dateUpdated DESC';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array(':userId'=>$_SESSION['userId']));
if ($r) { // The query has been executed succesfully
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while ($result=$stmt->fetch()) {
		$plans[]=$result;
	}
}


?>
<section class="profile">
<div class="profile">
<h4>Select the plan</h4>
<form action="gallery.php?page=addtoplan&galleryId=<?php echo $_GET['galleryId']; ?>" method="post">

<select name="plan" style="width:50px">
<option selected disabled>Select a plan</option>
<?php
foreach ($plans as $plan) {
	var_dump($plan);
	echo "<option value=\"{$plan['planId']}\">{$plan['title']}</option>";
}
?>
</select>
<input type="submit" name="submit" value="Add to plan"/>
</form>
</div>
</section>