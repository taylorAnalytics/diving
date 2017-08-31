<?php
/* The purpose of this script is to present the basic information about the dive shop
 */
?>

<section class="gallery">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Dive Shop Information</td></tr>
<tr><td>Dive Shop name: </td><td><?php echo $shop->userData['shopName']; ?></td></tr>
<tr><td>Website: </td><td><a class="normal" target="_blank" href="<?php echo $shop->userData['website']; ?>"><?php echo substr($shop->userData['website'], 7); ?></a></td></tr>
<tr><td>Email: </td><td><?php echo $shop->userData['email']; ?></td></tr>
<tr><td>Country: </td><td><?php echo $shop->userData['shopCountry']; ?></td></tr>
<tr><td>Address: </td><td><?php echo nl2br($shop->userData['address']); ?></td></tr>
<tr><td>Zip-code: </td><td><?php echo $shop->userData['zipCode']; ?></td></tr>
</table>
</section>