<?php
/* This script will show the dive shop profile. It will:
 * - get the diveshop object from the $_SESSION
 * - get the data for diveshop object from the MySQL database
 * - present the data in a form
 */

$shop=$_SESSION['diveShop'];
$shop->getUserData();

?>
<section class="profile">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Dive Shop Information</td></tr>
<tr><td>Dive Shop name: </td><td><?php echo $shop->userData['shopName']; ?></td></tr>
<tr><td>Website: </td><td><a class="normal" target="_blank" href="<?php echo $shop->userData['website']; ?>"><?php echo substr($shop->userData['website'], 7); ?></a></td></tr>
<tr><td>Country: </td><td><?php echo $shop->userData['shopCountry']; ?></td></tr>
<tr><td>Address: </td><td><?php echo nl2br($shop->userData['address']); ?></td></tr>
<tr><td>Zip-code: </td><td><?php echo $shop->userData['zipCode']; ?></td></tr>
</table>
</section>