<?php
$diver = $_SESSION['diver'];
$diver->getUserData();
?>

<section class="profile">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Personal information: </td><tr>
<tr><td>First name: </td><td><?php echo $diver->userData['firstName']; ?></td></tr>
<tr><td>Last name: </td><td><?php echo $diver->userData['lastName']; ?></td></tr>
<tr><td>Username: </td><td><?php echo $diver->userData['userName']; ?></td></tr>
<tr><td>Email address: </td><td><?php echo $diver->userData['email']; ?></td></tr>
<tr><td colspan="2" class="centered shadow">Dive experience</td></tr>
<tr><td>Certification agency: </td><td><?php echo $diver->userData['certAgency']; ?></td></tr>
<tr><td>Certification level: </td><td><?php echo $diver->userData['certLevel']; ?></td></tr>
<tr><td>Certification card number: </td><td><?php echo $diver->userData['certCardId']; ?></td></tr>
<tr><td>Diving since: </td><td><?php echo $diver->userData['divingSince']; ?></td></tr>
<tr><td>Number of dives: </td><td><?php echo $diver->userData['noOfDives']; ?></td></tr>
<tr><td colspan="2" class="centered shadow">Diver favorites</td></tr>
<tr><td>Your favorite dive site: </td><td><?php echo $diver->userData['favoriteSite'];?></td></tr>
<tr><td>Your best dive experience: </td><td><?php echo $diver->userData['bestExperience'];?></td></tr>
<tr><td>Your dive interests: </td><td><?php echo $diver->userData['diveInterests'];?></td></tr>


</table>
</section>
</div>