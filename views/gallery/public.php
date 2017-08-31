<?php
/* This script will present all the galleries from the database. It will:
 * - select all the galeries
 * - present the tile-info of each gallery
 * - link to a gallery when clicked on the tile-info
 * - show the galleries of the selected user
 */
echo '<section class="gallery">';
echo '<div class="title">
	<form action="gallery.php?page=public" method="post"><p class="search">Enter a tag: <input type="text" name="search"/><input type="submit" name="submit" value="search"/></p></form>
	<p class="search"><a href="gallery.php?page=public">View all</a></p>
	<h3>View & search other people galleries.</h3>
	<p>Click on the gallery to view the content.</p></div>';



if (isset($_GET['userId'])) { // A specific user has been selected:
	// Get this user's public galleries from database
	// Create the query:
	$q = 'SELECT galleryId, authorId, author, title, description, dateCreated, dateUpdated, fileNames, mainImage, path, public FROM galleries WHERE public=1 AND authorId=:authorId ORDER BY dateCreated DESC';
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':authorId' => $_GET['userId']));
	if ($r) {
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
		while ($gallery = $stmt->fetch()) {
			echo '<div class="gallery-tile"><a href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '">';
			echo '<div class="image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'" alt="' . $gallery->mainImage . '"/></div>';
			echo '<div class="gallery-info">';
			echo "<h4>$gallery->title</h4>";
			echo '<p>' . date('F j, Y', strtotime($gallery->dateCreated)) . '</p>';
			echo '<p>' . $gallery->author . '</p>';
			echo "<p class=\"description\">" . nl2br($gallery->description) . "</p>";
			echo '</div></a></div>';
		}
	}
} else { // No user has been selected
	if (isset($_POST['submit'])) { // A search form has been submitted
		// Get the galleries that have the searched word either in it's title or description
		// Create the query
		$keyWord = '%' . $_POST['search'] . '%';
		$q = 'SELECT * FROM galleries WHERE public=1 AND (tags LIKE :search) AND authorId!=:authorId ORDER BY dateCreated DESC';
		$stmt=$pdo->prepare($q);
		$r=$stmt->execute(array(':search' => $keyWord, ':authorId' => $_SESSION['userId']));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
			while ($gallery = $stmt->fetch()) {
				echo '<div class="gallery-tile"><a href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '">';
				echo '<div class="image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'" alt="' . $gallery->mainImage . '"/></div>';
				echo '<div class="gallery-info">';
				echo "<h4>$gallery->title</h4>";
				echo '<p>' . date('F j, Y', strtotime($gallery->dateCreated)) . '</p>';
				echo '<p>' . $gallery->author . '</p>';
				echo "<p class=\"description\">" . nl2br($gallery->description) . "</p>";
				echo '</div></a></div>';
			}
		}
		
	} else { // Nothing has been submitted, show all public galleries
		// Get all public galleries from database
		// Create the query:
		$q = 'SELECT galleryId, authorId, author, title, description, dateCreated, dateUpdated, fileNames, mainImage, path, public FROM galleries WHERE public=1 AND authorId!=:authorId ORDER BY dateCreated DESC';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':authorId' => $_SESSION['userId']));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Gallery');
			while ($gallery = $stmt->fetch()) {
				echo '<div class="gallery-tile"><a href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '">';
				echo '<div class="image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'" alt="' . $gallery->mainImage . '"/></div>';
				echo '<div class="gallery-info">';
				echo "<h4>$gallery->title</h4>";
				echo '<p>' . date('F j, Y', strtotime($gallery->dateCreated)) . '</p>';
				echo '<p>' . $gallery->author . '</p>';
				echo "<p class=\"description\">" . nl2br($gallery->description) . "</p>";
				echo '</div></a></div>';
			}
		}
	}
}
echo '</section>'; 
?>