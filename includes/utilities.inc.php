<?php
/* This is the utilities script. It does the set up & configuration used by other pages of the website */

// Autoload classes from the classes directory:
function class_loader($class) {
	require('classes/' . $class . '.php');
} // End of class_loader function
spl_autoload_register('class_loader');

// Start the session:
session_start();

$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : null;

// Create the database connection:
try {
	
	// Creat the object:
	$pdo = new PDO('mysql:dbname=diving;host=localhost', 'user', 'user');
	
} catch (PDOExcepton $e) {
	
	define('TITLE', 'Error!');
	include('icludes/header.inc.php');
	include('views/error.html');
	include('includes/footer.inc.php');
	exit();
}