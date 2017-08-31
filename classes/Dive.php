<?php
/* This script will create the class Dive. The class will have following attributes:
 * - diveId
 * - tripId
 * - userId
 * - date
 * - timeIn
 * - timeOut
 * - diveTime
 * - maxDepth
 * - units
 * - characteristics
 * - Gas (Air / EAN)
 * - O2 level
 * - country
 * - diveSite
 * - marineLife
 * - description
 * The class will have following methods:
 * - __construct()
 * - create()
 * - update()
 * - delete()
 * - getData()
 */

class Dive {
	// Define the attributes:
	public $diveId = null;
	public $tripId = null;
	public $userId = null;
	public $date = null;
	public $timeIn = null;
	public $timeOut = null;
	public $diveTime = null;
	public $maxDepth = null;
	public $units = null;
	public $characteristis = [];
	public $gas = null;
	public $o2Level = null;
	public $country = null;
	public $diveSite = null;
	public $marineLife = null;
	public $description = null;
	public $shopId = null;
	public $shopName = null;
	
	// Define the methods:
	// Define the constructor:
	public function __construct() {
		
	}
	// Define the create() method:
	public function create() {
		// Reference global variables:
		global $pdo;
		global $diveDate;
		global $timeIn;
		global $timeOut;
		global $o2level;
		global $characteristics;
		global $shopName;
		global $shopId;
		
		// Create the query:
		$q = 'INSERT INTO dives (tripId, userId, date, timeIn, timeOut, diveTime, maxDepth, units, characteristics, gas, o2Level, country, diveSite, marineLife, description, shopId)
		VALUES (:tripId, :userId, :date, :timeIn, :timeOut, :diveTime, :maxDepth, :units, :characteristics, :gas, :o2Level, :country, :diveSite, :marineLife, :description, :shopId)';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':tripId' => $_GET['tripId'], ':userId' => $_SESSION['userId'], ':date' => $diveDate, ':timeIn' => $timeIn, ':timeOut' => $timeOut,
		':diveTime' => $_POST['diveTime'], ':maxDepth' => $_POST['maxDepth'], ':units' => $_POST['units'], ':characteristics' => $characteristics, ':gas' => $_POST['gas'],
		':o2Level' => $o2level, ':country' => $_POST['country'], ':diveSite' => $_POST['diveSite'], ':marineLife' => $_POST['marineLife'], ':description' => $_POST['description'], ':shopId'=>$shopId, ':shopName'=>$shopName));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	// Define the getData() methods
	public function getData($diveId) {
		// Reference global variables:
		global $pdo;
		// Create the query:
		$q = 'SELECT * FROM dives WHERE diveId=:diveId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':diveId' => $diveId));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($result = $stmt->fetch()) {
				foreach ($result as $key => $value) {
					$this->$key = $value;
				}
			}
		} else {
			return FALSE;
		}
	}
	// Define the update() method:
	public function update($diveId) {
		// Reference global variables:
		global $pdo;
		global $diveDate;
		global $timeIn;
		global $timeOut;
		global $o2level;
		global $characteristics;
		global $shopName;
		global $shopId;
		
		// Create the query:
		$q = 'UPDATE dives SET date=:date, timeIn=:timeIn, timeOut=:timeOut, diveTime=:diveTime, maxDepth=:maxDepth, units=:units, characteristics=:characteristics,
		gas=:gas, o2Level=:o2Level, country=:country, diveSite=:diveSite, marineLife=:marineLife, description=:description, shopId=:shopId, shopName=:shopName WHERE diveId=:diveId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':date' => $diveDate, ':timeIn' => $timeIn, ':timeOut' => $timeOut, ':diveTime' => $_POST['diveTime'], ':maxDepth' => $_POST['maxDepth'],
		':units' => $_POST['units'], ':characteristics' => $characteristics, ':gas' => $_POST['gas'], ':o2Level' => $o2level, ':country' => $_POST['country'],
		':diveSite' => $_POST['diveSite'], ':marineLife' => $_POST['marineLife'], ':description' => $_POST['description'], ':shopId'=>$shopId, ':shopName'=>$shopName, ':diveId' => $_GET['diveId']));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function delete($diveId) {
		// Reference global variables:
		global $pdo;
		// Create the query
		$q = 'DELETE FROM dives WHERE diveId=:diveId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':diveId' => $diveId));
		if ($r) { // The query has been succesfully run
			return TRUE;
		} else {
			return FALSE;
		}
	}
}  
