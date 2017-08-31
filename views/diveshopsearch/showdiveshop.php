<?php
require('../../includes/utilities.inc.php');


echo $_GET['shopId'];
// Create the query to retrieve diveshop data from MySQL diveshops database
$data=[];
$q='SELECT * FROM diveshops WHERE shopId=:shopId';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array(':shopId'=>$_GET['shopId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($result=$stmt->fetch()) {
		$data=$result;
	}
}
// Create & run the query to retrieve diveshop offer data from MySQL diveshopoffer database
$q='SELECT * FROM diveshopoffer WHERE shopId=:shopId';
$stmt=$pdo->prepare($q);
$r=$stmt->execute(array('shopId'=>$_GET['shopId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($result=$stmt->fetch()) {
		$data['offerData']=$result;
	}
}
var_dump($data);

?>