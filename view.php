<?php
/* This script will enable you to view the article you have saved */

 
// Require the utilities file:
require('includes/utilities.inc.php');
// Define the title of the page & inlcude the header
define('TITLE', 'Diving is easy blog');
include('includes/header.inc.php');
if (isset($_GET['page'])) { // The blog post has been selected and should be presented in full
	// Create the query to select the article to be presented
	$q = 'SELECT articleId, title, content, mainImage, DATE_FORMAT(dateCreated, "%e %M %Y") AS dateCreated, author, tags FROM articles WHERE articleId=:articleId';
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':articleId' => $_GET['page']));
	if ($r) { // The query was succesfull
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');
		$article = $stmt->fetch();
		// Print the article
		echo '<section class="post">';
		echo "<div class=\"image-full\"><img src=\"uploads/$article->mainImage\" alt=\"$article->mainImage\" /></div>";
		echo "<h1>$article->title</h1>";
		echo "<div class=\"post\">$article->content</div>";
		echo '</section>';
	}
}
include('includes/footer.inc.php');
?>