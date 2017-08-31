<?php
/* The purpose of this script will be to show instrucor profile, without the possibility to edit it */

$instructor=$_SESSION['instructor'];
$instructor->getUserData();
?>
<section class="profile">
<table class="profile">
<tr><td colspan="2" class="centered shadow">Personal information: </td><tr>
<tr><td>First name: </td><td><?php echo $instructor->userData['firstName']; ?></td></tr>
<tr><td>Last name: </td><td><?php echo $instructor->userData['lastName']; ?></td></tr>
<tr><td>Username: </td><td><?php echo $instructor->userData['userName']; ?></td></tr>
<tr><td>Email address: </td><td><?php echo $instructor->userData['email']; ?></td></tr>
<tr><td>Country of Origin: </td><td><?php echo $instructor->userData['countryOfOrigin']; ?></td></tr>
<tr><td>Date of birth: </td><td><?php echo $instructor->userData['dateOfBirth']; ?></td></tr>
<tr><td colspan="2" class="centered shadow">Dive experience</td></tr>
<tr><td>Diving since: </td><td><?php echo $instructor->userData['divingSince']; ?></td></tr>
<tr><td>Number of dives: </td><td><?php echo $instructor->userData['noOfDives']; ?></td></tr>
<tr><td colspan="2" class="centered shadow">Teaching experience</td></tr>
<tr><td>Training agency: </td><td><?php echo $instructor->userData['trainOrg'];?></td></tr>
<tr><td>Instructor level: </td><td><?php echo $instructor->userData['instrLevel'];?></td></tr>
<tr><td>Teaching since: </td><td><?php echo $instructor->userData['teachingSince'];?></td></tr>
<tr><td>Number of certifications: </td><td><?php echo $instructor->userData['noOfCerts'];?></td></tr>
<tr><td>Country of residence: </td><td><?php echo $instructor->userData['countryOfResidence'];?></td></tr>
<tr><td>Specialties tought: </td><td><?php echo $instructor->userData['specialties']; ?></td></tr>

</table>
</section>
</div>

