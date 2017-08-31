<?php
/* This script will create a class DiveTrip. It will contain following attributes:
 * - tripId
 * - userId
 * - title
 * - destination
 * - description
 * - startDate
 * - endDate
 * - noOfDives
 * - galleryId
 * - maxDepth
 * - timeUnderwater
 * It will also have following methods:
 * - __construct()
 * - create()
 * - update()
 * - getData()
 * - delete()
 */
 
// Create the class:
class DiveTrip {
	
	// Define the attributes:
	public $tripId = null;
	public $userId = null;
	public $title = null;
	public $destination = null;
	public $description = null;
	public $startDate = null;
	public $endDate = null;
	public $noOfDives = null;
	public $galleryId = null;
	public $maxDepth = null;
	public $timeUnderwater = null;
	
	
	// Define the methods.
	// Define the constructor:
	public function __construct() {
	}
	// Define the create function
	public function create() {
		// Reference to gobal variables:
		global $pdo;
		global $startDate;
		global $endDate;
		global $galleryId;
		global $maxDepth;
		global $units;
		// Create the query to save the diveTrip data into MySQL database:
		$q = 'INSERT into trips (userId, title, destination, description, startDate, endDate, noOfDives, galleryId, maxDepth, units) VALUES (:userId, :title, :destination, :description, :startDate, :endDate, :noOfDives, :galleryId, :maxDepth, :units)';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':userId' => $_SESSION['userId'], ':title' => $_POST['title'], ':destination' => $_POST['destination'], ':description' => $_POST['description'], ':startDate' => $startDate, ':endDate' => $endDate,
		':noOfDives' => $_POST['noOfDives'], ':galleryId' => $galleryId, ':maxDepth' => $maxDepth, ':units' => $units));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function getData($tripId) {
		// Reference global variables:
		global $pdo;
		// Create the query
		$q = 'SELECT * FROM trips WHERE tripId=:tripId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':tripId' => $tripId));
		if ($r) { // The query has been succesfully executed
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($result = $stmt->fetch()) {
				foreach ($result as $key => $value) {
					$this->$key = $value;
				}
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}
	// Create the update method
	public function update($tripId) {
		// Reference global variables
		global $pdo;
		global $startDate;
		global $endDate;
		// Create the query
		$q = 'UPDATE trips SET title=:title, destination=:destination, description=:description, startDate=:startDate, endDate=:endDate,
		noOfDives=:noOfDives, galleryId=:galleryId, maxDepth=:maxDepth, units=:units WHERE tripId=:tripId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':title'=>$_POST['title'], ':destination'=>$_POST['destination'], ':description'=>$_POST['description'], ':startDate'=>$startDate, ':endDate'=>$endDate,
			':noOfDives'=>$_POST['noOfDives'], ':galleryId'=>$_POST['galleryId'], ':maxDepth'=>$_POST['maxDepth'], ':units'=>$_POST['units'], ':tripId'=>$_GET['tripId']));
		if ($r) { // The query has been succesfully run
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function delete($tripId) {
		// Reference global variables:
		global $pdo;
		// Create the query
		$q = 'DELETE FROM trips WHERE tripId=:tripId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':tripId' => $tripId));
		if ($r) { // The query has been succesfully run
			return TRUE;
		} else {
			return FALSE;
		}
	}
}



?>