<?php
/* This script will log the user out. It will:
 * - delete the session array
 * - unset the user object
 */
 
// Require the utilities file
require('includes/utilities.inc.php');

define('TITLE', 'Log out');
include('includes/header.inc.php');

// Clear the session data and destroy the session:
$_SESSION = [];
session_destroy();

header("Location:index.php");

include('includes/footer.inc.php');

?>

