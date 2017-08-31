<?php
/* This script will enable editing a specific gallery. It will:
 * - display all the gallery info within a form
 * - show a delete button with two other buttons: check all, uncheck all
 * - show "add new pictures" button
 * - show "save changes" button
 * - delete the pictures marked for deletion from the filesystem
 * - move uploaded files that need to be added to the gallery to a specific location in the filesystem
 */

echo '<section class="image">';

if (isset($_GET['galleryId'])) { // The galleryId has been provided
	$gallery = new Gallery();
	$gallery->getData($_GET['galleryId']);
	$files = explode(',', $gallery->fileNames);
	if(isset($_POST['submit'])) { // The form has been submitted
		// Validate the form
		$problem = FALSE;
		$fileType = FALSE;
		$fileSize = FALSE;
		// Make sure the title is there
		if (empty($_POST['title'])) {
			$problem = TRUE;
			echo '<p class="error">You can\'t leave the title empty</p>';
		}
		// Make sure the description is there
		if (empty($_POST['description'])) {
			$problem = TRUE;
			echo '<p class="error">You can\'t leave the description empty</p>';
		}
		// Check $_FILES['images']
		if (isset($_FILES['images']) && ($_FILES['images']['size'][0] > 0)) {
			$fileType = FALSE;
			$fileSize = FALSE;
			// Check for the filetype
			foreach ($_FILES['images']['type'] as $type) {
				if(!in_array($type, ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'])) { // One of the files is not an image
					$problem = TRUE;
					$fileType = TRUE;
				}
			} // End of fileType FOREACH
			// Check for the size
			foreach ($_FILES['images']['size'] as $size) {
				if ($size > 1000000) { // One of the files is too large
					$problem = TRUE;
					$fileSize = TRUE;
				} // End of IF
			} // End of fileSize FOREACH
			if ($fileType) { echo '<p class="error">Some of your images files are not images</p>'; }
			if ($fileSize) { echo '<p class="error">Some of your images files are too large</p>';}
		} // End of $_FILES['images'] IF
		
		if (!$problem) { // The whole form has been validated
			// Delete the pictures from the filesystem & keep the list of those to update fileNames in MySQL database - store them in an array?
			// Check if any files where selected for deletion
			if (isset($_POST['delete'])) {
				$filesToDelete = $_POST['delete'];
				foreach ($filesToDelete as $fileName) { // Loop through the array & delete each file that is on the list
					if (file_exists($gallery->path . $fileName)) { // Check if the file actually exists
						unlink($gallery->path . $fileName);
					}
				} // End of FOREACH
			} // End of IF
			
			// Move new pictures into the gallery folder in the filesystem & also store their names in some array
			if (isset($_FILES['images']) && ($_FILES['images']['size'][0] > 0)) { // Some files have been uploaded
				$uploadProblem = FALSE; // A variable checking if there was a problem with the upload
				foreach ($_FILES['images']['tmp_name'] as $key => $file) {
					if (move_uploaded_file($file, $gallery->path . $_FILES['images']['name'][$key])) {// The file has been moved succesfully
						// Well, nothing happens :) It just worked
					} else {
						$uploadProblem = TRUE;
					} // End of IF
				} // End of FOREACH
				if ($uploadProblem) { // There was at least one problem with the upload
					echo '<p class="error">Some files could not be uploaded</p>';
				}
			}
			
			// Update the MySQL database through a new method of Gallery object
			// Create the variables that will be used in a query:
			$data=[];
			$data['title'] = $_POST['title'];
			$data['description'] = $_POST['description'];
			$data['tags'] = $_POST['tags'];
			// Check all the files in the gallery directory & if they're files, add them to an array
			$dirList = scandir($gallery->path);
			foreach ($dirList as $element) {
				if (is_file($gallery->path . $element)) {
					$imageList[] = $element;
				} // End of IF
			} // End of FOREACH
			if (isset($imageList)) {
				$data['fileNames'] = implode(',',$imageList);
			} else { // There were no files in the directory, so the array is not existent}
				$data['fileNames'] = '';
			} 
			if (isset($_POST['mainImage'])) {
				$data['mainImage'] = $_POST['mainImage'];
			} else {
				$data['mainImage'] = $gallery->mainImage;
			}
			if ($_POST['status'] == 'public') {
				$data['public'] = TRUE;
			} else {
				$data['public'] = FALSE;
			}
			$data['galleryId'] = $gallery->galleryId;
			
			if ($gallery->update($data)) {
				echo '<p class="error">Your gallery has been updated</p>';
				$gallery->getData($_GET['galleryId']);
				$files = explode(',', $gallery->fileNames);
			} else {
				echo '<p class="error">Something went wrong. Could not update your gallery</p>';
			}
		}
			
	}
	// Print out each element of gallery info as an form element
	// Print the "go back" button
	echo '<a class="button-2" href="gallery.php?page=view&galleryId=' . $gallery->galleryId . '">Back to view gallery</a>';
	// Echo the key div container & the image on the left
	echo '<div class="gallery">
		<div class="main-image"><img src="./galleries/' . $gallery->galleryId . '/' . $gallery->mainImage .'"/></div>';
	// Open up the form 
	echo '<form action="gallery.php?page=edit&galleryId=' . $gallery->galleryId . '" method="post" enctype="multipart/form-data">';
	// Open the div on the right & the content of it (description & tags)
	echo '<div class="right">
	<p>Description:</p><br><textarea name="description" cols="50" rows="2">' . $gallery->description .'</textarea>
	<p>Tags:</p><br><input type="text" name="tags" size="50" value="'. $gallery->tags . '"></p>
	</div>';
	// Open the central div & the content (title & status)
	echo '<div class="center"><p>Title:</p><br>
	<p><input type="text" name="title" value="' . $gallery->title . '"></p><br>
	<p>Status: </p><br>
	<p><input type="radio" name="status" value="public" ';
	if ($gallery->public) echo ' checked'; 
	echo '>Public<input type="radio" name="status" value="private" ';
	if (!$gallery->public) echo ' checked';
	echo '>Private</p></div>';
	// Print the div with buttons to manage images
	echo '<div class="left">
	<div class="third"><p>Change the main image</p><br>
	<input type="button" value="Select new image" onclick="openSelect();"></div>
	<div class="third"><p>Add new pictures:</p><br><input type="file" name="images[]" multiple="multiple"></div>
	<div class="third"><p>Delete pictures</p><br>
	<input type="button" value="Delete pictures" onclick="openDeleteMenu();"><br>
	<input type="button" value="Check all" class="hidDel hidden" onclick="checkAll();">
	<input type="button" value="Uncheck all" class="hidDel hidden" onclick="unCheckAll();"></div>';
	
	echo '<div class="left"><input type="submit" name="submit" value="Save changes"></div>';
	echo '</div></div>';
	foreach ($files as $file) {
		echo '<div class="gallery-image"><img src="./galleries/' . $gallery->galleryId . '/' . $file . '"/><br>
		<p class="checkDelete hidden">Delete:<input type="checkbox" name="delete[]" class="centered checkDelete hidden" value="' . $file . '"/></p><br>
		<p class="checkMain hidden">Main:<input type="radio" name="mainImage" class="centered checkMain hidden" value="' . $file . '"/></p></div>';
	}
	
	echo '</form>';
}

echo '</section>';
?>