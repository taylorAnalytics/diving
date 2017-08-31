<?php
/* This script defines the class Comment. The class has following attributes:
 * - commentId
 * - authorId
 * - author
 * - content
 * - dateCreated
 * - posted
 * - articleId
 * The class has following methods:
 * - construct()
 * - save()
 * - post()
 * - edit()
 * - delete()
 * - get()
 */

class Comment {
// Define the attributes of the comment
	public $authorId = null;
	public $author = null;
	public $content = null;
	public $dateCreated = null;
	public $posted = false;
	public $articleId = null;
	
// Define the methods
	// Define the method construct()
	public function __construct() {
	}
	// Define the method post()
	public function save() {
		// Reference global variables:
		global $pdo;
		global $article;
		global $diver;
		global $instructor;
		global $diveShop;
		// Create the $author variable, which is depending on the userType
		switch ($_SESSION['userType']) {
			case 'Diver':
				$this->author = $diver->userData['firstName'] . ' ' . $diver->userData['lastName'];
				break;
			case 'Instructor':
				$this->author = $instructor->userData['firstName'] . ' ' . $instructor->userData['lastName'];
				break;
			case 'DiveShop':
				$this->author = $diveShop->userData['shopName'];
				break;
		}
		
		
		// Create the query to save the comment in the database
		$q = 'INSERT INTO comments (authorId, author, content, posted, articleId) VALUES (:authorId, :author, :content, 1, :articleId)';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':authorId' => $_SESSION['userId'], ':author' => $this->author, ':content' => $_POST['comment'], ':articleId' => $article->articleId));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}


?>