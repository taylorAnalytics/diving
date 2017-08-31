<?php
/* This script will show all the images from a specific gallery. It will:
 * - retrieve all the gallery info, including the file list from MySQL
 * - explode the list of files into an array
 * - display each file in a formatted way
 * - create a link under each file that will show this file in a full window
 * - enable going into modification mode that will let you:
 *	- delete files
 *	- add new files
 *	- change title & description
 *	- delete the whole gallery
 */

echo '<section class="image">';

if (isset($_GET['galleryId'])) {
	$gallery = new Gallery();
	$gallery->getData($_GET['galleryId']);
	$files = explode(',', $gallery->fileNames);
	// Check if an image has been selected
	if (isset($_GET['image'])) { // It has, show the image only
		// Create & show the links for top paragraphs (previous, back, next)
		$n = array_search($_GET['image'], $files); // The index of current picture in the array of all the pictures within that gallery
		if ($n > 0) { // It's not the first picture
			echo '<p class="left"><a class="button" href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '&image=' . $files[$n-1] . '">Previous picture</a></p>';
		}
		if ($n < count($files) - 1) { // It's not the lasdt piture}
			echo '<p class="right"><a class="button" href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '&image=' . $files[$n+1] . '">Next picture</a></p>';
		}
		echo '<p class="centered"><a class="button" href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '">Back to the gallery</a></p>';
		echo '<div></div>';
		echo '<img class="main" src="./galleries/' . $gallery->galleryId . '/' . $_GET['image'] . '"/>';
	} else { // It hasn't, show the whole gallery
		
	echo '<a class="button-2" href="gallery.php?page=public">Back to all galleries</a>
		<a class="button-2" href="gallery.php?page=public&userId=' . $gallery->authorId . '">Other galleries by ' . $gallery->author . '</a>
		<a class="button-2" href="gallery.php?page=addtoplan&galleryId='.$gallery->galleryId.'">Add to a plan</a>';
	echo '<div class="gallery">
		<img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'"/>';
	echo '<div class="right"><p>Description:<br><em>' . $gallery->description .'</em></p><br>
		<p>Tags: <br><em>' . $gallery->tags . '</em></p></div>';
	echo '<div class="center"><h3>' . $gallery->title . '</h3>';
	echo '<p>Status: <em>';
	echo ($gallery->public ? 'public' : 'private');
	echo '</em></p><br>';
	echo '<p>Author: <em>' . $gallery->author . '</em></p><br>';
	echo '<p>Created: <em>' . date('F d, Y', strtotime($gallery->dateCreated)) . '</em></p><br>
		<p>Updated: <em>' . date('F d, Y', strtotime($gallery->dateUpdated)) . '</em></p></div>';
	
	echo '</div>';
	
	foreach ($files as $file) {
		echo '<div class="gallery-image"><a href="gallery.php?page=publicview&galleryId=' . $gallery->galleryId . '&image=' . $file . '">
		<img src="./galleries/' . $gallery->galleryId . '/' . $file . '"/></a></div>';
	}
	}
	
}

echo '</section>';
?>