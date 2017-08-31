<?php
/* This script defines the class DivePlan. It will contain following attributes:
 * - planId
 * - userId
 * - title
 * - destination
 * - date
 * - diveShops
 * - articles
 * - galleries
 * It will have following methods:
 * - __construct()
 * - create()
 * - update()
 * - geData()
 * - delete()
 */

// Create the class
class DivePlan {

	// Define the attributes:
	public $planId=null;
	public $userId=null;
	public $title=null;
	public $destination=null;
	public $date=null;
	public $description=null;
	public $diveShops=null;
	public $articles=null;
	public $galleries=null;

	// Define the methods
	// Define the constructor
	public function __construct() {
	// What should be here so that the method is valuable??
	}
	// Define create() method
	public function create() {
		// Reference the global variables:
		global $pdo;
		global $date;
		// Create the query to insert the newly created dive trip in the database
		$q='INSERT INTO plans (userId, title, destination, date, description) VALUES (:userId, :title, :destination, :date, :description)';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':userId'=>$_SESSION['userId'], ':title'=>$_POST['title'], ':destination'=>$_POST['destination'], ':date'=>$date, ':description'=>$_POST['description']));
		if ($r) { // The query has been sucesfully run
			return TRUE;
		} else { // There was a problem with the query 
			print_r($stmt->errorInfo());
			return FALSE;
		}
	} // End of the create() method
	// Define getData() method
	public function getData($planId) {
		// Reference the global variables:
		global $pdo;
		// Create the query to retrieve all the DivePlan data
		$q='SELECT * FROM plans WHERE planId=:planId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':planId'=>$planId));
		if ($r) { // The query has been succesfully run
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($result=$stmt->fetch()) { // The data has been succesfully retrieved
				foreach ($result as $key=>$value) { // Fill the object attributes with the results of the query
					$this->$key=$value;
				}
				return TRUE;
			} else { // The data has not been retrieved
				return FALSE;
			}

		}
	}
}

?>