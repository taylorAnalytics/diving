<?php
/* This script will enable the user, who also is an author to write, edit, save & submit an article. It would include following functionalities:
 * - the form to write the article
 * - the sql queries to save the article, update it after it'd been edited.
 * - the functionality to submit it to the web admin
 */

// Create the form:
if (isset($_POST['submit'])) {
	// Make sure the "article" object exists & is stored in the $_SESSION
	if (isset($_SESSION['article'])) {
		$article = $_SESSION['article'];
	} else {
		$article = new Article($diver->userId);
		$_SESSION['article'] = $article;
	}
	// Check if a file had been submitted
	if ($_FILES) {
		// Transfer the filename into $_POST so that it can later be used in the save() method of article object
		if (move_uploaded_file($_FILES['mainImage']['tmp_name'], "uploads/{$_FILES['mainImage']['name']}")) {
			$_POST['mainImage'] = $_FILES['mainImage']['name'];
		} else {
			echo '<p class="error">Could not save your file</p>';
			$_POST['mainImage'] = null;
		}
	} else {
		$_POST['mainImage'] = null;
	}
	
	
	// Call the "save" method of the article object so that the article is being saved into the mysql database
	$diver = $_SESSION['diver'];
	$diver->getUserData();
	$article->save();
	
} else {
	unset($article);
	unset($_SESSION['article']);
}

?>

<section class="profile">
<form action="diverprofile.php?page=write" enctype="multipart/form-data" method="post">
<table class="profile">
<tr><td class="centered shadow">Title:</td></tr>
<tr><td class="centered"><input type="text" name="title" size="100" 
<?php if (isset($article->title)) echo "value=\"$article->title\"";?>
/></td></tr>
<tr><td class="centered shadow">Content:</td></tr>
<tr><td class="centered"><textarea name="content" cols="100" rows="25">
<?php if (isset($article->content)) echo "$article->content";?>
</textarea></td></tr>
<tr><td class="centered shadow">Tags:</td></tr>
<tr><td class="centered"><input type="text" name="tags" size="100" 
<?php if (isset($article->tags)) echo " value=\"$article->tags\"";?>
/></td></tr>
<tr><td class="centered shadow">Post image:</td></tr>
<tr><td class="centered"><input type="file" name="mainImage"/></td></tr>
<input type="hidden" name="MAX_FILE_SIZE" value="500000">
<tr><td class="centered"><input type="submit" name="submit" value="Save your article" /></td></tr>
<?php if (isset($article->articleId)) { echo '<tr><td class="centered"><a target="_blank" href="view.php?page=' . $article->articleId . '">Preview your article</a></td></tr>'; }?>
<tr><td class="centered"><a href="diverprofile.php?page=submit">Submit your article to be posted</a></td></tr>
</table>
</form>
</section>