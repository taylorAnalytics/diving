<?php
/* The purpose of this script is to present the galleries created by the dive shop
 */

echo '<section class="gallery">';
// Echo a title for the galleries
echo '<div class="title"><h3>The galleries created by '.$shop->userData['userName'].'.</h3></div>';

// Get all the user's galleries out of the database
// Create & run the query:
$q = 'SELECT galleryId, authorId, title, description, dateCreated, dateUpdated, fileNames, mainImage, path, public FROM galleries WHERE authorId=:authorId';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array('authorId' => $shop->userData['userId']));
if ($r) { // The query has been run succesfully
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
	while ($gallery = $stmt->fetch()) { // Fetch each of the user's galleries from the database
		echo '<div class="gallery-tile"><a href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '">';
		echo '<div class="image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'" alt="' . $gallery->mainImage . '"/></div>';
		echo '<div class="gallery-info">';
		echo "<h4>$gallery->title</h4>";
		if ($gallery->public == '1') { echo '<p>Public</p>';} else { echo '<p>Private</p>';}
		echo '<p>' . date('F j, Y', strtotime($gallery->dateCreated)) . '</p>';
		echo "<p class=\"description\">" . nl2br($gallery->description) . "</p>";
		echo '</div></a></div>';
		
	}
}


echo '</section>';
?>