<?php
/* The purpose of this script is to show the dive shop prife offer. It will:
 * - get the current offer from the mysql database
 * - show the table with all the information
 */

echo '<section class="gallery">';
// Echo a title for the galleries
echo '<div class="title"><h3>' . $_SESSION['userName'] . ', these are the details of your price offer.</h3></div>';
if ($shop->getOffer($_SESSION['userId'])) {
	echo "<table class=\"profile\">
	<tr><td>Currency: </td><td>{$shop->offerData['currency']}</td></tr>
	<tr><td>DSD price: </td><td>{$shop->offerData['DSDprice']}</td></tr>
	<tr><td>OWD price: </td><td>{$shop->offerData['OWDprice']}</td></tr>
	<tr><td>AOWD price: </td><td>{$shop->offerData['AOWDprice']}</td></tr>
	<tr><td>RESCUE price: </td><td>{$shop->offerData['RESCUEprice']}</td></tr>
	<tr><td>DM price: </td><td>{$shop->offerData['DMprice']}</td></tr>
	<tr><td>IDC price: </td><td>{$shop->offerData['IDCprice']}</td></tr>
	<tr><td>Manuals included: </td><td>";
	if ($shop->offerData['manual']==1) { echo 'Yes';} else {echo 'No';} 
	echo '<tr><td>Accommodation included: </td><td>';
	if ($shop->offerData['accommodation']==1) { echo 'Yes';} else {echo 'No';}
echo "<tr><td>Number of dives in a day trip: </td><td>{$shop->offerData['daytripDives']}</td></tr>
	<tr><td>Price of a day trip: </td><td>{$shop->offerData['daytripPrice']}</td></tr>
	<tr><td>Price per dive in a day trip: </td><td>{$shop->offerData['daytripPricePerDive']}</td></tr>
	<tr><td>Number of dives in a package: </td><td>{$shop->offerData['packageDives']}</td></tr>
	<tr><td>Price of a package: </td><td>{$shop->offerData['packagePrice']}</td></tr>
	<tr><td>Price per dive in a package: </td><td>{$shop->offerData['packagePricePerDive']}</td></tr>
	</table>";

} else {
	echo '<p>You do not have your offer in the database yet. Please use the <a href="diveshopprofile.php?page=editoffer">\'Edit price offer\'</a> link to create one</p>';
	
}
echo '</section>';

?>