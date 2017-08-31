<?php
/* This page defines the Instructor class which is derived from the User class.
 * The class contains those attributes:
 * - userId
 * - email address
 * - password
 * - userData - array of all user informations
 * - loggedIn:Boolean
 * The class contains following methods:
 * - logIn()
 * - logOut()
 * - register()
 * - getUserData()
 * - isAuthor()
 */
 
 class Instructor extends User {
	
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
				$_SESSION['instructor'] = $this;
				$_SESSION['userType'] = 'Instructor';
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
					$q = 'INSERT INTO instructors (userId, divingSince, noOfDives, trainOrg, instrLevel, noOfCerts, countryOfResidence, teachingSince) 
					VALUES (:userId, :divingSince, :noOfDives, :trainOrg, :instrLevel, :noOfCerts, :countryOfResidence, :teachingSince)';
					$stmt = $pdo->prepare($q);
					$r = $stmt->execute(array(':userId' => $this->userId, ':divingSince' => $_POST['divingSince'], ':noOfDives' => $_POST['numberOfDives'], ':trainOrg' => $_POST['trainAgency'], ':instrLevel' => $_POST['instLevel'], ':noOfCerts' => $_POST['numberOfCerts'], ':countryOfResidence' => $_POST['countryOfResidence'], ':teachingSince' => $_POST['teachingSince']));
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
		instructors.divingSince, instructors.noOfDives, instructors.trainOrg, instructors.instrLevel, instructors.noOfCerts, instructors.countryOfResidence, instructors.teachingSince, instructors.specialties
		FROM users LEFT JOIN instructors ON users.userId=instructors.userId WHERE users.email=:email';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':email' => $this->email));
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetch();
		$this->userData = $result;
	}
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
	} // End of th method definition
	// Create the update method
	public function update($userId) {
		// Reference global vaiables
		global $pdo;
		global $dateOfBirth;
		global $specialties;
		// Create a variable that will be used to check if both queries have been succesfully run
		$query1 = FALSE;
		$query2 = FALSE;
		// Create the query to update data in the MySQL database in table users
		$q = 'UPDATE users SET firstName=:firstName, lastName=:lastName, userName=:userName, countryOfOrigin=:countryOfOrigin, dateOfBirth=:dateOfBirth WHERE userId=:userId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':firstName'=>$_POST['firstName'], ':lastName'=>$_POST['lastName'], ':userName'=>$_POST['username'], ':countryOfOrigin'=>$_POST['countryOfOrigin'], ':dateOfBirth'=>$dateOfBirth, ':userId'=>$userId));
		if ($r) {
			$query1=TRUE;
		}
		// Create the query to update data in the MySQL database in table instructors
		$q='UPDATE instructors SET divingSince=:divingSince, noOfDives=:noOfDives, divingSince=:divingSince, trainOrg=:trainOrg, instrLevel=:instrLevel, noOfCerts=:noOfCerts, teachingSince=:teachingSince, countryOfResidence=:countryOfResidence, specialties=:specialties WHERE userId=:userId';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':divingSince'=>$_POST['divingSince'], ':noOfDives'=>$_POST['noOfDives'], 'divingSince'=>$_POST['divingSince'], ':trainOrg'=>$_POST['trainOrg'], 'instrLevel'=>$_POST['instrLevel'], 'noOfCerts'=>$_POST['noOfCerts'], 'teachingSince'=>$_POST['teachingSince'], ':countryOfResidence'=>$_POST['countryOfResidence'], ':specialties'=>$specialties, ':userId'=>$userId));
		if ($r) {
			$query2 = TRUE;
		}
		if ($query1==TRUE && $query2==TRUE) {
			return TRUE;
		}
	} // End of update method
 } // End of class definition