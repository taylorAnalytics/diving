<?php
/* This script will enable the diver to edit an article that he had already created. It will:
 * - get the articleId from $_GET
 * - get the article content from the database
 * - put the content into the form
 * - update the article in the database on "form submit"
 * - submit the form for posting on clicking the button "submit" (different then form submit)
 */

// Create a new article object
$article = new Article();
$article->getData($_GET['articleId']);
$_SESSION['article'] = $article;

// Check if the form had been submitted:
if (isset($_POST['submit'])) {
	
	if ($_FILES) {
		// Transfer the filename into $_POST so that it can later be used in the save() method of article object
		if (move_uploaded_file($_FILES['mainImage']['tmp_name'], "uploads/{$_FILES['mainImage']['name']}")) {
			$_POST['mainImage'] = $_FILES['mainImage']['name'];
		} else { // Could not move the file, the old file (if existed) stays as the main file
			echo '<p class="error">Could not save your file</p>';
			$_POST['mainImage'] = $article->mainImage;
		}
	} else { // No new file uploaded, the old file (if existed) stays as the main file
		$_POST['mainImage'] = $article->mainImage;
	}
	// Run the article method "update"
	$article->update();
	
}

// Get the article data from the database:
if ($article->getData($_GET['articleId'])) {
	echo '<section class="profile">
	<form action="diverprofile.php?page=editarticle&articleId=' . $article->articleId . '" enctype="multipart/form-data" method="post">
		<table class="profile">
		<tr><td class="centered shadow">Title:</td></tr>
		<tr><td class="centered"><input type="text" name="title" size="100" value="' . $article->title . '"/></td></tr>
		<tr><td class="centered shadow">Content:</td></tr>
		<tr><td class="centered"><textarea name="content" cols="100" rows="25">' . $article->content. '</textarea></td></tr>
		<tr><td class="centered shadow">Tags:</td></tr>
		<tr><td class="centered"><input type="text" name="tags" size="100" value="' . $article->tags . '"/></td></tr>
		<tr><td class="centered shadow">Post image:</td></tr>
		<tr><td><div class="image-half"><img src="uploads/' . $article->mainImage . '" alt="' . $article->mainImage . '" /></div></td></tr>
		<tr><td class="centered"><input type="file" name="mainImage"/></td></tr>
		<input type="hidden" name="MAX_FILE_SIZE" value="50000000">
		<tr><td class="centered"><a target="_blank" href="view.php?page=' . $article->articleId . '">Preview your article</a></td></tr>
		<tr><td class="centered"><input type="submit" name="submit" value="Save your article" /></td></tr>';
	echo '</table></form></section>';
	
}
	



?>