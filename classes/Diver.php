<?php
/* This page defines the Diver class which is derived from the User class.
 * The class contains those attributes:
 * - userId
 * - userName
 * - email address
 * - password
 * - userData - array of all user informations
 * - loggedIn:Boolean
 * The class contains following methods:
 * - logIn()
 * - logOut()
 * - register()
 * - getUserData()
 * - updateData()
 * - isAuthor()
 */
 
 class Diver extends User {
	
	public $userId = null;
	public $userName = null;
	public $email = null;
	public $password = null;
	public $userData = array();
	public $loggedIn = FALSE;
	
	// Create a constructor with a single parameter - email address, which will also serve as the username:
	public function __construct($email) {
		$this->email = $email;
	}
	// Create the login function:
	public function logIn($em, $p) {
		// Reference to global $pdo variable
		global $pdo;
		// Retrieve user credentials from the MySQL database
		$q = 'SELECT userId, userName, email FROM users WHERE email=:email AND password=:password';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $em, ':password' => $p));
		echo '<p class="error">wtf</p>';
		// Check the results of the query
		if ($r) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			print_r($result);
			if ($result) {
				$this->loggedIn = TRUE;
				$_SESSION['loggedIn'] = $this->loggedIn;
				$this->userId = $result['userId'];
				$this->userName = $result['userName'];
				$_SESSION['diver'] = $this;
				$_SESSION['userType'] = 'Diver';
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
		global $dateOfBirth;
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
			$userName = $_POST['firstName'] . ' ' . $_POST['lastName'];
			// Create the MySQL query to insert the user into the database:
			$q = 'INSERT INTO users (email, password, userType, firstName, lastName, userName, countryOfOrigin, dateOfBirth)
			VALUES (:email, :password, :userType, :firstName, :lastName, :userName, :countryOfOrigin, :dateOfBirth)';
			$stmt = $pdo->prepare($q);
			$r = $stmt->execute(array(':email' => $em, ':password' => $p, ':userType' => $_POST['userType'], ':firstName' => $_POST['firstName'], ':lastName' => $_POST['lastName'], ':userName' => $userName, ':countryOfOrigin' => $_POST['countryOfOrigin'], ':dateOfBirth' => $dateOfBirth));
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
					$q = 'INSERT INTO divers (userId, certAgency, certLevel, divingSince, noOfDives) VALUES (:userId, :certAgency, :certLevel, :divingSince, :noOfDives)';
					$stmt = $pdo->prepare($q);
					$r = $stmt->execute(array(':userId' => $this->userId, ':certAgency' => $_POST['certAgency'], ':certLevel' => $_POST['certLevel'], ':divingSince' => $_POST['divingSince'], ':noOfDives' => $_POST['numberOfDives']));
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
		$q = 'SELECT users.userId, users.email, users.firstName, users.lastName, users.userName, users.countryOfOrigin, users.dateOfBirth, 
		divers.certAgency, divers.certLevel, divers.certCardId, divers.divingSince, divers.noOfDives, divers.favoriteSite, divers.bestExperience, divers.diveInterests
		FROM users LEFT JOIN divers ON users.userId=divers.userId WHERE users.email=:email';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $this->email));
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		$this->userData = $result;
	}
	public function updateUserData() {
		// Reference to global variables:
		global $pdo;
		// Create the query to retrieve user data from the database:
		$q = 'UPDATE users, divers
		SET users.email=:email, users.firstName=:firstName, users.lastName=:lastName, users.userName=:userName, users.countryOfOrigin=:countryOfOrigin, 
		divers.certAgency=:certAgency, divers.certLevel=:certLevel, divers.certCardId=:certCardId,
		divers.divingSince=:divingSince, divers.noOfDives=:noOfDives, divers.favoriteSite=:favoriteSite, 
		divers.bestExperience=:bestExperience, divers.diveInterests=:diveInterests
		WHERE users.userId=divers.userId AND users.userId=:userId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $_POST['email'], ':firstName' => $_POST['firstName'], ':lastName' => $_POST['lastName'], ':userName' => $_POST['userName'], ':countryOfOrigin' => $_POST['countryOfOrigin'],
		':certAgency' => $_POST['certAgency'], ':certLevel' => $_POST['certLevel'], ':certCardId' => $_POST['certCardId'],
		':divingSince' => $_POST['divingSince'], ':noOfDives' => $_POST['noOfDives'], ':favoriteSite' => $_POST['favoriteSite'],
		':bestExperience' => $_POST['bestExperience'], ':diveInterests' => implode(",", $_POST['diveInterests']), ':userId' => $this->userId));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		} // End of the qery IF
	} // End of the function
	public function isAuthor() {
		// Reference to global variable:
		global $pdo;
		// Create the query to check if the user is an author
		$q = 'SELECT isAuthor FROM users WHERE userId=:userId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':userId' => $this->userId));
		$result = $stmt->fetchColumn();
		if($result) {
			return TRUE;
		} else {
			return FALSE;
		} // End of IF
	} // End of the function
		
 } // End of the class definition