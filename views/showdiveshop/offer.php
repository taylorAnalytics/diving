<?php
/* The purpose of this script is to present the offer of the dive shop
 */
?>

<section class="gallery">
<table class="profile">
<tr><td>Currency: </td><td><?php echo $shop->offerData['currency']; ?></td></tr>
<tr><td colspan="2" class="centered shadow">Recreational courses prices</td></tr>
<tr><td>Discover Scuba Diving: </td><td><?php echo number_format($shop->offerData['DSDprice'],0,'.',' ');?></td></tr>
<tr><td>Open Water Diver: </td><td><?php echo number_format($shop->offerData['OWDprice'],0,'.',' ');?></td></tr>
<tr><td>Advanced Open Water Diver: </td><td><?php echo number_format($shop->offerData['AOWDprice'],0,'.',' ');?></td></tr>
<tr><td>Rescue Diver: </td><td><?php echo number_format($shop->offerData['RESCUEprice'],0,'.',' ');?></td></tr>
<tr><td>Manuals included: </td><td><?php
if ($shop->offerData['manual']==1) {
	echo 'Yes';
} else {
	echo 'No';
}
?></td></tr>
<tr><td>Accommodation included: </td><td><?php
if ($shop->offerData['accommodation']==1) {
	echo 'Yes';
} else {
	echo 'No';
}
?></td></tr>
<tr><td colspan="2" class="centered shadow">Professional courses prices</td></tr>
<tr><td>Dive Master: </td><td><?php echo number_format($shop->offerData['DMprice'],0,'.',' ');?></td></tr>
<tr><td>Instructor Development Course: </td><td><?php echo number_format($shop->offerData['IDCprice'],0,'.',' ');?></td></tr>
<tr><td colspan="2" class="centered shadow">Fun diving prices</td></tr>
<tr><td>Daytrip - number of dives: </td><td><?php echo number_format($shop->offerData['daytripDives'],0,'.',' ');?></td></tr>
<tr><td>Daytrip - price: </td><td><?php echo number_format($shop->offerData['daytripPrice'],0,'.',' ');?></td></tr>
<tr><td>Daytrip - price per dive: </td><td><?php echo number_format($shop->offerData['daytripPricePerDive'],0,'.',' ');?></td></tr>
<tr><td>Package - number of dives: </td><td><?php echo number_format($shop->offerData['packageDives'],0,'.',' ');?></td></tr>
<tr><td>Package - price: </td><td><?php echo number_format($shop->offerData['packagePrice'],0,'.',' ');?></td></tr>
<tr><td>Package - price per dive: </td><td><?php echo number_format($shop->offerData['packagePricePerDive'],0,'.',' ');?></td></tr>
</table>
</section>