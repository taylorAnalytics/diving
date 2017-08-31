<?php
/* This page defines the DiveShop class which is derived from the User class.
 * The class contains those attributes:
 * - userId
 * - userName
 * - email address
 * - password
 * - userData - array of all user informations
 * - loggedIn:Boolean
 * - offerData - array of dive shop's price offer
 * The class contains following methods:
 * - logIn()
 * - logOut()
 * - register()
 * - getUserData()
 * - isAuthor()
 * - getOfferData();
 */
 
 class DiveShop extends User {
	
	public $userId = null;
	public $userName = null;
	public $email = null;
	public $password = null;
	public $userData = array();
	public $loggedIn = FALSE;
	public $offerData= array();
	
	// Create a constructor with a single parameter - email address, which will also serve as the username:
	public function __construct($email) {
		$this->email = $email;
	}
	// Create the login function:
	public function logIn($em, $p) {
		// Reference to global $pdo variable
		global $pdo;
		// Retrieve user credentials from the MySQL database
		$q = 'SELECT userId, email, userName FROM users WHERE email=:email AND password=:password';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $em, ':password' => $p));
		
		// Check the results of the query
		if ($r) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result) {
				$this->loggedIn = TRUE;
				$_SESSION['loggedIn'] = $this->loggedIn;
				$this->userId = $result['userId'];
				$this->userName = $result['userName'];
				$_SESSION['diveShop'] = $this;
				$_SESSION['userType'] = 'DiveShop';
				$_SESSION['userName'] = $this->userName;
				header("Location: index.php");
				exit;	
			} else {
				echo '<p class="error">Your email or password are incorrect. Try again</p>';
			}
			
		} else {
			$this->loggedIn = FALSE;
		}
	}
	// Create the logout function:
	public function logOut() {
		
	}
	// Create the register function
	// The function is using 2 parameters: email, password
	public function register($em, $p) {
		// Declare global variables to be used in the function
		global $pdo;
		// Check if the user doesn't already exist:
		// Retrieve data for the inputed email from database:
		$q = 'SELECT userId, email FROM users WHERE email=:email';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $em));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if($result) { // The user exists in the database
			echo '<p class="error">The user already exists in the database</p>';
			return FALSE;
		} else { // The user does not exist in the database yet
			// Create the MySQL query to insert the user into the database:
			$q = 'INSERT INTO users (email, password, userType, userName)
			VALUES (:email, :password, :userType, :userName)';
			$stmt = $pdo->prepare($q);
			$r = $stmt->execute(array(':email' => $em, ':password' => $p, ':userType' => $_POST['userType'], ':userName' => $_POST['shopName']));
			// Check if the query has been executed:
			if ($r) { // The user has been created
				// Get the userId
				$q = 'SELECT userId FROM users WHERE email=:email';
				$stmt = $pdo->prepare($q);
				$r = $stmt->execute(array(':email' => $em));
				if ($r) {
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$result = $stmt->fetch();
					$this->userId = $result['userId']; // The userId has been retrieved and can be furtherly used
					// Create the query to insert data to "divers" database, using the userId retrieved from "users" database
					$q = 'INSERT INTO diveShops (userId, shopName, website, shopCountry, address, zipCode) 
					VALUES (:userId, :shopName, :website, :shopCountry, :address, :zipCode)';
					$stmt = $pdo->prepare($q);
					$r = $stmt->execute(array(':userId' => $this->userId, ':shopName' => $_POST['shopName'], ':website' => $_POST['shopWebsite'], ':shopCountry' => $_POST['shopCountry'], ':address' => $_POST['address'], ':zipCode' => $_POST['zipCode']));
					if ($r) { // The query has been executed`
						// Finish off the function with returning TRUE
						return TRUE;	
					} else {
						return FALSE;
					}
				} else { // userId could not be retrieved
					return FALSE;
				}
			} else { // The user had not been created
				return FALSE;
			} // End of create the user IF
		} // End of the main IF
	}// End of register function
	public function getUserData() {
		// Reference to global variables:
		global $pdo;
		// Create the query to retrieve user data from the database:
		$q = 'SELECT users.userId, users.email, users.userName, diveShops.shopId, diveShops.shopName, diveShops.website, diveShops.shopCountry, diveShops.address, diveShops.zipCode
		FROM users LEFT JOIN diveShops ON users.userId=diveShops.userId WHERE users.email=:email';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $this->email));
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		$this->userData = $result;
	}
	public function isAuthor() {
		
	}
	// Define the update() method
	public function update($userId) {
		// Reference to global variables:
		global $pdo;
		// Create the query to update data in MySQL
		$q='UPDATE users, diveshops SET users.userName=:userName, diveshops.shopName=:shopName, diveshops.website=:website, diveshops.shopCountry=:shopCountry, diveshops.address=:address, diveshops.zipCode=:zipCode WHERE diveshops.userId=users.userId AND users.userId=:userId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':userName'=>$_POST['userName'], ':shopName'=>$_POST['shopName'], ':website'=>$_POST['website'], ':shopCountry'=>$_POST['country'], ':address'=>$_POST['address'], ':zipCode'=>$_POST['zipCode'], ':userId'=>$userId));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}	
 	} // End of update() method
 	// Define the getOffer() method
 	public function getOffer($userId) {
 		// Reference to global variables:
 		global $pdo;
 		// Create the query to retrieve offer data from mysql database:
 		$q = 'SELECT * FROM diveshopoffer WHERE userId=:userId';
 		$stmt=$pdo->prepare($q);
 		$r=$stmt->execute(array(':userId'=>$userId));
 		if ($r) {
 			$stmt->setFetchMode(PDO::FETCH_ASSOC);
 			$result=$stmt->fetch();
 			$this->offerData = $result;
 			return TRUE;
 		} // End of $r IF
 	} // End of the getOffer() method
 	// Define the createOffer method
 	public function createOffer($userId) {
 		// Reference to global variables:
 		global $pdo;
 		global $daytripPricePerDive;
 		global $packagePricePerDive;
 		// Create the query to insert the offer into mysql database
 		$q = 'INSERT INTO diveshopoffer (shopId, userId, currency, DSDprice, OWDprice, AOWDprice, RESCUEprice, DMprice, IDCprice, manual, accommodation, daytripDives, daytripPrice, daytripPricePerDive, packageDives, packagePrice, packagePricePerDive) 
 		VALUES (:shopId, :userId, :currency, :DSDprice, :OWDprice, :AOWDprice, :RESCUEprice, :DMprice, :IDCprice, :manual, :accommodation, :daytripDives, :daytripPrice, :daytripPricePerDive, :packageDives, :packagePrice, :packagePricePerDive)';
 		$stmt=$pdo->prepare($q);
 		$r=$stmt->execute(array(':shopId'=>$this->userData['shopId'], ':userId'=>$userId, ':currency'=>$_POST['currency'], ':DSDprice'=>$_POST['DSDprice'], ':OWDprice'=>$_POST['OWDprice'], ':AOWDprice'=>$_POST['AOWDprice'], ':RESCUEprice'=>$_POST['RESCUEprice'], ':DMprice'=>$_POST['DMprice'], ':IDCprice'=>$_POST['IDCprice'], ':manual'=>$_POST['manual'], ':accommodation'=>$_POST['accommodation'], ':daytripDives'=>$_POST['daytripDives'], ':daytripPrice'=>$_POST['daytripPrice'], ':daytripPricePerDive'=>$daytripPricePerDive, ':packageDives'=>$_POST['packageDives'], ':packagePrice'=>$_POST['packagePrice'], ':packagePricePerDive'=>$packagePricePerDive));
 		if ($r) {
 			return TRUE;
 		} else {
 			return FALSE;
 		}
 	} // End of createOffer() method
 	// Define the updateOfer() method
 	public function updateOffer($userId) {
 		// Reference to global variables:
 		global $pdo;
 		global $daytripPricePerDive;
 		global $packagePricePerDive;
 		// Create the query to update the offer in the mysql database
 		$q = 'UPDATE diveshopoffer SET currency=:currency,  DSDprice=:DSDprice, OWDprice=:OWDprice, AOWDprice=:AOWDprice, RESCUEprice=:RESCUEprice, DMprice=:DMprice, IDCprice=:IDCprice, manual=:manual, accommodation=:accommodation, daytripDives=:daytripDives, daytripPrice=:daytripPrice, daytripPricePerDive=:daytripPricePerDive, packageDives=:packageDives, packagePrice=:packagePrice, packagePricePerDive=:packagePricePerDive WHERE userId=:userId';
 		$stmt=$pdo->prepare($q);
 		$r=$stmt->execute(array(':userId'=>$userId, ':currency'=>$_POST['currency'], ':DSDprice'=>$_POST['DSDprice'], ':OWDprice'=>$_POST['OWDprice'], ':AOWDprice'=>$_POST['AOWDprice'], ':RESCUEprice'=>$_POST['RESCUEprice'], ':DMprice'=>$_POST['DMprice'], ':IDCprice'=>$_POST['IDCprice'], ':manual'=>$_POST['manual'], ':accommodation'=>$_POST['accommodation'], ':daytripDives'=>$_POST['daytripDives'], ':daytripPrice'=>$_POST['daytripPrice'], ':daytripPricePerDive'=>$daytripPricePerDive, ':packageDives'=>$_POST['packageDives'], ':packagePrice'=>$_POST['packagePrice'], ':packagePricePerDive'=>$packagePricePerDive));
 		if ($r) {
 			return TRUE;
 		} else {
 			return FALSE;
 		} // eND OF if
 		
 	} // End of updateOffer() method
}
