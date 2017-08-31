<?php
/* The purpose of this script is to perform a specific action on the dive shop
 */

?>

<section class="gallery">
<div>
<p><a href="showdiveshop.php?page=addtotrip&email=<?php echo $shop->userData['email']; ?>" class="button-2">Add to a trip</a>
<a href="showdiveshop.php?page=addtoplan&email=<?php echo $shop->userData['email']; ?>" class="button-2">Add to a plan</a></p>
</div>
</section>