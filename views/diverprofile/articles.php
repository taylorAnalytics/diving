<?php
/* This script will display bookmarked articles. It will:
 * - Create the list of bookmarked articles based on one query
 * - Select those articles from database & display their brief info
 * - Contain a link to view the article in a separate window
 */

// Create the section container
echo '<section class="profile">';
echo '<div class="title"><h3>' . $_SESSION['userName'] . ', here you can view your bookmarked articles.</h3></div>';
// Create the list of bookmarked articles:
// Create the query:
$q = 'SELECT articleId FROM bookmarkedArticles WHERE userId=:userId';
$stmt=$pdo->prepare($q);
$r = $stmt->execute(array(':userId' => $_SESSION['userId']));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$i = 0;
	while($result = $stmt->fetch()) {
		$i++;
		// Retrieve each article from the database
		$q = 'SELECT articleId, title, mainImage, dateCreated, author FROM articles WHERE articleId=:articleId';
		$stmt2 = $pdo->prepare($q);
		$r2 = $stmt2->execute(array(':articleId' => $result['articleId']));
		$stmt2->setFetchMode(PDO::FETCH_CLASS, 'Article');
		$article = $stmt2->fetch();
		echo '<div class="article"><a href="blog.php?page='.$article->articleId.'" target="_blank">
		<div class="image"><img src="uploads/' . $article->mainImage . '" alt="'. $article->mainImage .'"></div>';
		echo '<div><h2>'.$article->title.'</h2>
		<p>Author: ' . $article->author . '</p>
		<p>Created: ' . date('F d, Y', strtotime($article->dateCreated)) . '</p></div>';
		echo '</a></div>';
	}
	if ($i == 0) {
		echo '<div class="article"><p>You do not have any bookmarked articles</p></div>';
	}
	
}
echo '</section>';
?>