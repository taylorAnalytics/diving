<?php
/* This script enables the diver to manage the articles they wrote. It will:
 * - show the list of all the articles they wrote
 * - show the status of each article (draft / submitted / posted)
 * - have an "edit" button that will open up the article for editing
 */
 
// Create the list of all articles written by the diver
// Create the query to get the list out of the database:
$q = 'SELECT articleId, title, content, dateCreated, dateUpdated, mainImage, galleryPath, tags, submitted, posted FROM articles WHERE userId=:userId ORDER BY dateCreated DESC';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':userId' => $diver->userId));
if ($r) {
	echo '<table class="article-list"><tr class="shadow"><th>Article no.</th><th>Title</th><th class="date">Date created</th><th class="date">Date updated</th><th>Tags</th><th>Status</th><th></th></tr>';
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');
	$i=1;
	while ($article = $stmt->fetch()) {
		echo "<tr class=\"bottom\"><td class=\"centered\">$i</td><td>$article->title</td><td class=\"date\">" . date('j M Y', strtotime($article->dateCreated)) . "</td><td class=\"date\">" . date('j M Y', strtotime($article->dateUpdated)) . "</td><td>$article->tags</td><td class=\"status\">";
		if ($article->posted == 1) {
			echo ' Posted';
		} elseif ($article->submitted == 1) {
			echo ' Submitted';
		} else {
			echo ' Draft';
		}
		echo "</td><td class=\"edit\"><a href=\"diverprofile.php?page=editarticle&articleId=$article->articleId\">Edit</a></td></tr>";
		$i++;
	}
	echo '</table>';
}

?>