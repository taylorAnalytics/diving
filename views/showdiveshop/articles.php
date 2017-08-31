<?php
/* The purpose of this script is to present the articles written by the dive shop
 */

// Create the section container
echo '<section class="gallery">';
echo '<div class="title"><h3>The articles authored by ' . $shop->userData['userName'].'</h3></div>';
// Create the list of authored articles:
// Create the query:
$q = 'SELECT articleId FROM articles WHERE userId=:userId';
$stmt=$pdo->prepare($q);
$r = $stmt->execute(array(':userId' => $shop->userData['userId']));
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
		echo '<div class="article"><p>'.$shop->userData['userName'].' has not written any articles</p></div>';
	}
	
}
echo '</section>';

?>