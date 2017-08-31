<?php
/* This script will show all the plans & enable creating new ones. It will:
 * - Retrieve all the user's plans from the database
 * - Display the basic info of each plan
 * - link to the detaild view of a single plan
 * - have a button that would link to the "create new plan" page
 */

if (isset($_GET['planId'])) {
	include('viewplan.php');
} else {

?>
<section class="profile">
	<div class="title">
		<h3>Plan your dive trips</h3>
		<p>Create your unique dive trip plans. Add elements to the plan, calculate cost, gather information.</p>
	</div>
	<a href="trips.php?page=createplan" class="button-2">Create a new plan</a>
<?php
// Retrieve all the user's plans from the database
$q='SELECT * FROM plans WHERE userId=:userId ORDER BY dateUpdated DESC';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array(':userId'=>$_SESSION['userId']));
if ($r) { // The query has been succesfully run
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'DivePlan');
	while ($plan=$stmt->fetch()) {
		echo '<div class="trip">
			<a class="button" href="trips.php?page=plan&planId='.$plan->planId.'">View</a>
			<a class="button" href="trips.php?page=editplan&planId='.$plan->planId.'">Edit</a>
		
		<h3>'.$plan->title.'</h3>
		<p>Destination: '.$plan->destination.'</p>
		<p>Date: '.$plan->date.'</p>
		</div>';
	}
}

?>



</section>
<?php
}
?>

