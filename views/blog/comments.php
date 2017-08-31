<?php
/* This script will enable posting and viewing comments to the particular blog post. It will:
 * - Show a comment form
 * - Show the list of comments
 */

// Open the section that will contain all the comments
include('includes/userupload.php');


echo '<section class="post">';
echo '<h2 class="left">Post a comment:</h2>';
// Check for the form submission:
if (isset($_POST['submit']) && $_POST['comment'] != '') { // The form has been submitted
	$_POST['comment'] = nl2br($_POST['comment']);
	$comment = new Comment();
	if ($comment->save()) {
		echo '<p class="error">Your comment has been saved</p>';
	}
}

// Check if the user is logged in & if so, print the comment form (commenting is only available to logged in users);
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == TRUE) {
	include('views/blog/commentform.html');
} else {
	echo '<p class="error">Log in if you want to post a comment</p>';
}
// Count how many comments there are for a specific article
$q = 'SELECT COUNT(commentId) FROM comments WHERE articleId=:articleId';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':articleId' => $article->articleId));
if ($r) {
	$commentCount = $stmt->fetchColumn();
}

echo "<h2 class=\"left\">Comments ($commentCount):</h2>";
// Create the query to get all of the comments for this particular article
$q = 'SELECT commentId, authorId, author, content, dateCreated FROM comments WHERE articleId=:articleId ORDER BY dateCreated DESC';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':articleId' => $article->articleId));
if ($r) {
	$stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
	while ($comment = $stmt->fetch()) {
		echo '<div class="comment">';
		echo "<p class=\"content\">$comment->content</p>";
		echo "<p class=\"date\">" . date('j M Y, G:i', strtotime($comment->dateCreated)) . " by " . $comment->author . "</p>";
		echo '</div>';
	} // End of WHILE
} // End of IF

echo '</section>';
?>