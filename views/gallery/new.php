<?php 
/* This script creates a new gallery. It will:
 * - have a form that will upload the pictures
 * - handle the form
 * - handle the uploaded files - create a directory for them & move them to that directory
 * - save the data about the gallery in mysql database
 */

// Validate the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // The form has been submitted
	$problem = FALSE;
	$fileType = FALSE;
	$fileSize = FALSE;
	
	// Check all the fields:
	if (empty($_POST['title'])) {
		echo '<p class="error">Please enter the title for your gallery</p>';
		$problem = TRUE;
	}
	if (empty($_POST['description'])) {
		echo '<p class="error">Please enter the description for your gallery</p>';
		$problem = TRUE;
	}
	if (!isset($_FILES) || empty($_FILES['images']['name'][0])) {
		echo '<p class="error">Please select some files to be uploaded into your gallery</p>';
		$problem = TRUE;
	} else { // If some files have been updated, check for their filetype & size of each file
		foreach ($_FILES['images']['type'] as $key => $value) {
			if (!in_array($value, ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'])) { // Check if the file is an image
				$problem = TRUE;
				$fileType = TRUE;
			}
		} // End of foreach
		foreach ($_FILES['images']['size'] as $key => $value) {
			if ($value > 1000000) {
				$problem = TRUE;
				$fileSize = TRUE;
			}
		} // End of foreach
	}
	if ($fileType) { echo '<p class="error">Some of your files are not images. Make sure you upload only images</p>';}
	if ($fileSize) { echo '<p class="error">Some of your files exceed the size limit of 1MB</p>';}
	if (!$problem) { // The form has been validated
		echo '<p class="error">Your gallery has been created.<br>Click on "show all your galleries" to browse it.<br>Click on "Create a new gallery" on the left to create a new gallery.</p>';
		// Create new object of Class Gallery and use it's "create" method to save key informations into the database
		
		if (!isset($_SESSION['gallery'])) { // The Gallery object is not existing yet
			$gallery = new Gallery();
			$gallery->create($_SESSION['userId']);
			$_SESSION['gallery'] = $gallery;
		} else {
			$gallery = $_SESSION['gallery'];
		}
		// Define the gallery directory
		$dir = 'C:/xampp/htdocs/diving/galleries/';
		// Check if the directory already exists. If it doesn't, create one
		$galDirs = scandir($dir); // Returns the array of all directories & files in the galleries folder (should be only directories);
		if (!in_array($gallery->galleryId, $galDirs)) { // There is no directory named by the galleryId (which would be the dir containing the images)
			// Create the directory
			mkdir($dir . $gallery->galleryId);
		}
		// Move uploaded files to the directory
		// Create an array of uploaded filenames:
		
		$uploadProblem = FALSE;
		// Loop through each of the files from $_FILES
		foreach ($_FILES['images']['tmp_name'] as $key => $file) {
			if (move_uploaded_file($file, $dir . $gallery->galleryId . '/' . $_FILES['images']['name'][$key])) {// The file has been succesfully moved
				// Put the name of the file into the array
				// $uploadedFiles[] = $_FILES['images']['name'][$key];
			} else { // The file could not be uploaded
				$uploadProblem = TRUE;
			}
		} // End of foreach
		if ($uploadProblem) {
			echo '<p class="error">Some of the files could not be copied into the directory</p>';
		}
		// Create the list of all the files in the path directory
		$uploadedFiles = [];
		$tmpList = scandir($dir . $gallery->galleryId . '/');
		foreach ($tmpList as $element) {
			if (is_file($dir . $gallery->galleryId . '/' . $element)) {
				$uploadedFiles[] = $element;
			}
		}
		// Create the variables to use in the MySQL query:
		$mainImage = $uploadedFiles[0];
		$fileList = implode(',', $uploadedFiles);
		$path = 'C:/xampp/htdocs/diving/galleries/' . $gallery->galleryId . '/';
		if ($gallery->updateFiles($fileList, $mainImage, $path)) {
			echo '<p class="error">Your files have been saved</p>';
		} else {
			echo '<p class="error">Could not update file information in the database</p>';
		}
	}
} else {
	unset ($_SESSION['gallery']);
}

?>
<section class="gallery">
<div class="title">
<h3>Create your own gallery.</h3>
<p>Fill in the form & upload your pictures.</p>
</div>
<form action="gallery.php?page=new" method="post" enctype="multipart/form-data">
<table class="gallery-create">
<tr><td>Name of the gallery: </td><td><input type="text" name="title" size="30"></td></tr>
<tr><td>Description: </td><td><textarea name="description" cols="30" rows="3"></textarea></td></tr>
<tr><td>Tags: </td><td><input type="text" name="tags" size="30"></td></tr>
<tr><td>Gallery status: </td><td><input type="radio" name="public" value="0" checked="checked" />Private <input type="radio" name="public" value="1">Public</td></tr>
<tr><td colspan="2" class="centered"><input type="file" name="images[]" multiple="multiple" value="select your pictures"/></td></tr>
<tr><td colspan="2" class="centered"><input type="submit" name="submit" value="Save your pictures"></td></tr>
</table>
</form>
</section>
