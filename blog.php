<?php
/* This script will upload 11 most recent articles from the database and view it. It will:
 * - create the list of 11 articles & get some of their data from MySQL
 * - post their basic info, their picture 2 divs per row (the latest one is full page);
 * - include the link to the full article
 */
 
// Require the utilities file:
require('includes/utilities.inc.php');
// Define the title of the page & inlcude the header
define('TITLE', 'Diving is easy blog');
include('includes/header.inc.php');
include('includes/functions.php');

// Define the query to upload the article headlines from the database:
$q = 'SELECT articleId, title, mainImage, DATE_FORMAT(dateCreated, "%e %M %Y") AS dateCreated, author, tags FROM articles WHERE posted=:posted ORDER BY dateCreated DESC LIMIT 11';
$stmt = $pdo->prepare($q);
$r = $stmt->execute(array(':posted' => 1));
if ($r) { // The statement has been executed
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	while ($result = $stmt->fetch()) {
		$posts[] = $result;
	}
} else {
	echo '<p class="error">There was an error. Could not upload the blog articles</p>';
}

if (isset($_GET['page'])) { // The blog post has been selected and should be presented in full
	// Create the query to select the article to be presented
	$q = 'SELECT articleId, title, content, mainImage, DATE_FORMAT(dateCreated, "%e %M %Y") AS dateCreated, author, tags FROM articles WHERE articleId=:articleId';
	$stmt = $pdo->prepare($q);
	$r = $stmt->execute(array(':articleId' => $_GET['page']));
	if ($r) { // The query was succesfull
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');
		$article = $stmt->fetch();
		
		if (isset($_SESSION['userId'])) {
			
		$bookmarked=FALSE;
		// Check if the bookmark form has been submitted
		if (isset($_POST['sub'])) { // It has been submitted
			if (isset($_POST['bookmark'])) { // The article has been selected to be bookmarked
				// Create & run the MySQL query:
				$q = 'INSERT INTO bookmarkedArticles (articleId, userId, bookmarked) VALUES (:articleId, :userId, 1)';
				$stmt=$pdo->prepare($q);
				$r = $stmt->execute(array(':articleId' => $article->articleId, ':userId' => $_SESSION['userId']));
				if ($r) { // The data has been inputted into the database
					$bookmarked = TRUE;
				}
			} else { // The article has been de-bookmarked
				// Create & run the MySQL query:
				$q = 'DELETE FROM bookmarkedArticles WHERE articleId=:articleId AND userId=:userId';
				$stmt=$pdo->prepare($q);
				$r = $stmt->execute(array(':articleId' => $article->articleId, ':userId' => $_SESSION['userId']));
				if ($r) {
					$bookmarked=FALSE;
				}
			}
		} else { // The form has not been submitted
			// Check if the article is bookmarked in the database
			$bookmarked=FALSE;
			// Create & run the query
			$q = 'SELECT bookmarked FROM bookmarkedArticles WHERE articleId=:articleId AND userId=:userId';
			$stmt=$pdo->prepare($q);
			$r = $stmt->execute(array(':articleId' => $article->articleId, ':userId' => $_SESSION['userId']));
			if ($r) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				if(empty($result)) {
					$bookmarked=FALSE;
				} else {
					$bookmarked=TRUE;
				}
			}
		}
		} // End of IF userId
		
		
		
		
		// Print the article
		echo '<section class="post">';
		echo "<div class=\"image-full\"><img src=\"uploads/$article->mainImage\" alt=\"$article->mainImage\" /></div>";
		echo "<h1>$article->title</h1>";
		if (isset($bookmarked)) {
			echo "<form action=\"blog.php?page=$article->articleId\" method=\"post\">
				<p>Bookmark this article: <input type=\"checkbox\" name=\"bookmark\" onchange=\"this.form.submit()\"";
			if ($bookmarked) { echo ' checked="checked"';}
			echo '/></p><input type="hidden" name="sub" value="submitted"></form>';
		}
		echo "<div class=\"post\">$article->content</div>";
		echo '</section>';
	}
	// Check the index of the article in the array of all the articles from the database
	$n = findKey($posts, 'articleId', $article->articleId);
	// Print the links to the previous and the next articles
	echo '<section class="post">';
	if ($n > 0) {
		echo '<p class="previous-post">';
		echo '<a href="blog.php?page=' . $posts[$n-1]['articleId'] . '">Previous: ' . $posts[$n-1]['title'] . '</a>';
		echo '</p>';
	}
	if ($n < count($posts)-1) {
		echo '<p class="next-post">';
		echo '<a href="blog.php?page=' . $posts[$n+1]['articleId'] . '">Next: ' . $posts[$n+1]['title'] . '</a>';
		echo '</p>';
	}
	echo '</section>';
	include('views/blog/comments.php');
} else { // Show the list of the blog posts
		
	
	// Print the section container
	echo '<section class="container">';
	// Print the latest post as a main-post (full page width)
	echo "<div class=\"main-post\">";
	echo "<div class=\"image-full\"><a href=\"blog.php?page={$posts[0]['articleId']}\"><img src=\"uploads/{$posts[0]['mainImage']}\" alt=\"{$posts[0]['mainImage']}\" /></a></div>";
	echo "<a href=\"blog.php?page={$posts[0]['articleId']}\"><h1>{$posts[0]['title']}</h1></a>";
	echo "<h3>Published on {$posts[0]['dateCreated']} by {$posts[0]['author']}</h3>";
	$tags = explode(",", $posts[0]['tags']);
		echo '<p>';
		foreach ($tags as $t) {
			$t = trim($t);
			echo $t;
		}
		echo '</p></div>';
	// print the following 10 posts, 2 divs per page width
	for ($i = 1; $i < count($posts);$i++) {
		echo "<div class=\"half-post\">";
		echo "<div class=\"image-half\"><span class=\"helper\"></span><a href=\"blog.php?page={$posts[$i]['articleId']}\"><img src=\"uploads/{$posts[$i]['mainImage']}\" alt=\"{$posts[$i]['mainImage']}\" /></a></div>";
		echo "<div class=\"post-header\"><a href=\"blog.php?page={$posts[$i]['articleId']}\"><h2>{$posts[$i]['title']}</h2></a>";
		echo "<h4>Published on {$posts[$i]['dateCreated']} by {$posts[$i]['author']}</h4>";
		$tags = explode(",", $posts[$i]['tags']);
		echo '<p>';
		foreach ($tags as $t) {
			$t = trim($t);
			echo $t;
		}
		echo '</p></div></div>';
		
	} // End of foreach
	// End the container section
	echo '</section>';
}

include('includes/footer.inc.php');
?>