<?php
/* This script defines the abstract class User.
 * The class has following methods:
 * - login()
 * - logout()
 * - register()
 * - getUserData()
 * - isAuthor()
 */
 
abstract class User {
	
	// The class has no attributes
	
	// Construtor:
	abstract protected function __construct($email);
	// Method to log in:
	abstract protected function login($em, $p);
	// Method to log out:
	abstract protected function logout();
	// Method to register the user in database:
	abstract protected function register($em, $p);
	// Method to get the name of the user:
	abstract protected function getUserData();
	// Method to check if the user is also an author for blog posts:
	abstract protected function isAuthor();	
		
} // End of User class.

