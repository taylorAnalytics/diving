<?php
/* This script creates the class Article. The class will have following attributes:
 * - articleId
 * - title
 * - content
 * - tags
 * - dateCreated
 * - dateUpdated
 * - mainImage
 * - galleryPath
 * - author
 * - authorId
 * It will also have following methods:
 * - construct()
 * - save()
 * - submit()
 * - post()
 * - update()
 * - getData()
 */
 
class Article {
	
	// Define the attributes:
	public $articleId = null;
	public $title = null;
	public $content = null;
	public $tags = null;
	public $dateCreated = null;
	public $dateUpdated = null;
	public $mainImage = null;
	public $galleryPath = null;
	public $author = null;
	public $userId = null;
	public $submitted = null;
	public $posted = null;
	
	// Define the methods:
	// Define the constructor:
	public function __construct() {
	}
	
	// Define the "save" function
	public function save() {
		// Create the reference to global variables:
		global $pdo;
		global $diver;
		// Check if the article already has an articleId (had already been saved before):
		if (isset($this->articleId)) { // The article has an articleId, therefore it means it had already been saved in the database under this articleId
			$q = 'UPDATE articles SET title=:title, content=:content, mainImage=:mainImage, tags=:tags WHERE articleId=:articleId';
			$stmt = $pdo->prepare($q);
			$r = $stmt->execute(array(':title' => $_POST['title'], ':content' => $_POST['content'], ':mainImage' => $_POST['mainImage'], ':tags' => $_POST['tags'], ':articleId' =>$this->articleId));
			if ($r) {
				echo '<p class="error">Your article has been saved</p>';
				$this->title = $_POST['title'];
				$this->content = $_POST['content'];
				$this->tags = $_POST['tags'];
				$this->mainImage = $_POST['mainImage'];
				$this->submitted = 0;
				$this->posted=0;
			} else {
				echo '<p class="error">Something went wrong. Could not save your article</p>';
			}
		} else { // The article doesn't have an articleId yet, so the data needs to be inserted, not updated
			$q = 'INSERT INTO articles (title, content, mainImage, tags, author, userId) VALUES (:title, :content, :mainImage, :tags, :author, :userId)';
			$stmt = $pdo->prepare($q);
			$r = $stmt->execute(array(':title' => $_POST['title'], ':content' => $_POST['content'], ':mainImage' => $_POST['mainImage'], ':tags' => $_POST['tags'], ':author' => $diver->userData['firstName'] . ' ' . $diver->userData['lastName'], ':userId' => $diver->userId));
			if ($r) {
				echo '<p class="error">Your article has been saved.</p>';
				$this->title = $_POST['title'];
				$this->content = $_POST['content'];
				$this->tags = $_POST['tags'];
				$this->mainImage = $_POST['mainImage'];
				$this->submitted = 0;
				$this->posted=0;
				// Run the query to get the missing article data and load it into the object attributes:
				$q = 'SELECT articleId, dateCreated, dateUpdated, author, userId FROM articles WHERE title=:title';
				$stmt = $pdo->prepare($q);
				$r = $stmt->execute(array(':title' =>$_POST['title']));
				if ($r) { // The query was succesfully run
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$result = $stmt->fetch();
					// set the values of the missing object attributes based on the results of the query:
					$this->articleId = $result['articleId'];
					$this->dateCreated = $result['dateCreated'];
					$this->dateUpdated = $result['dateUpdated'];
					$this->author = $result['author'];
				} else {
					echo '<p class="error">Something went wrong. Could not load the rest of the data</p>';
				}
			} else {
				echo '<p class="error">Something went wrong. Could not save your article</p>';
			}
		} 
	} // End of the "save" function
	public function update() {
		// Rereference to the global variables
		global $pdo;
		// Create the query to update the data in the database:
		$q = 'UPDATE articles SET title=:title, content=:content, mainImage=:mainImage, tags=:tags, submitted=0, posted=0 WHERE articleId=:articleId';
		$stmt=$pdo->prepare($q);
		$r = $stmt->execute(array(':title' => $_POST['title'], ':content' => $_POST['content'], ':mainImage' => $_POST['mainImage'], ':tags' => $_POST['tags'], ':articleId' => $_GET['articleId']));
		if ($r) {
			echo '<p class="error">Your article have been saved. You need to re-submit it if you want it to get posted</p>';
		} else {
			echo '<p class="error">Something went wrong. Could not save your article</p>';
		}
	} // End of "update" function
	public function getData($articleId) {
		// Reference to the global variables
		global $pdo;
		// Create the query to get the data out of the database:
		$q = 'SELECT articleId, title, content, dateCreated, dateUpdated, mainImage, galleryPath, author, userId, tags, submitted, posted FROM articles WHERE articleId=:articleId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':articleId' => $articleId));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$result = $stmt->fetch();
			if ($result) {
				$this->articleId = $result['articleId'];
				$this->title = $result['title'];
				$this->content = $result['content'];
				$this->dateCreated = $result['dateCreated'];
				$this->dateUpdated = $result['dateUpdated'];
				$this->mainImage = $result['mainImage'];
				$this->galleryPath = $result['galleryPath'];
				$this->author = $result['author'];
				$this->userId = $result['userId'];
				$this->tags = $result['tags'];
				$this->submitted = $result['submitted'];
				$this->posted = $result['posted'];
				return TRUE;
			}
		} else {
			return FALSE;
		}
	} // End of getData() method
	
	public function submit() {
		// Reference to global variables
		global $pdo;
		// Create the query to update the "submitted" field in the MySQL database
		$q = 'UPDATE articles SET submitted=1 WHERE articleId=:articleId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array('articleId' => $this->articleId));
		if ($r) {
			return TRUE;
		} else {
			return FALSE;
		}
	} // End of the submit method
}