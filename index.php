<?php
require('includes/utilities.inc.php');

define('TITLE', 'Diving is easy');
include('includes/header.inc.php');
if (isset($_SESSION['diver'])) {
	$diver = $_SESSION['diver'];
}
if(isset($_SESSION['instructor'])) {
	$instructor = $_SESSION['instructor'];
}
if(isset($_SESSION['diveShop'])) {
	$diveShop = $_SESSION['diveShop'];
}
include('views/index.html');
include('includes/footer.inc.php');
?>
