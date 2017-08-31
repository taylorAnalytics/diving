<?php
/* This script will create the Class of Gallery. Gallery will have following attributes:
 * - galleryId
 * - authorId
 * - author
 * - title
 * - description
 * - tags
 * - dateCreated
 * - dateUpdated
 * - fileNames
 * - mainImage
 * - path
 * - public
 * It will have following methods:
 * - __construct()
 * - create()
 * - update()
 * - getData()
 * - delete()
 */
 
Class Gallery {
	// Define the attibutes:
	public $galleryId = null;
	public $authorId = null;
	public $author = null;
	public $title = null;
	public $description = null;
	public $tags = null;
	public $dateCreated = null;
	public $dateUpdated = null;
	public $fileNames = [];
	public $mainImage = null;
	public $path = null;
	public $public = null;
	
	// Define the methods:
	// Define the constructor:
	public function __construct() {
		
	}
	// Define the method create():
	public function create($authorId) {
		$this->authorId = $authorId;
		// Reference to global variables:
		global $pdo;
		// Create the query that will save the gallery information in the  MySQL database:
		$q = 'INSERT INTO galleries (authorId, author, title, description, tags, public) VALUES (:authorId, :author, :title, :description, :tags, :public)';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':authorId' => $_SESSION['userId'], ':author' => $_SESSION['userName'], ':title' => $_POST['title'], ':description' => $_POST['description'], ':tags' => $_POST['tags'], ':public' => $_POST['public']));
		if ($r) {// The query has been succesfully run}
			// Get the 
			$q = 'SELECT LAST_INSERT_ID()';
			$stmt = $pdo->prepare($q);
			$r = $stmt->execute();
			if ($r) { // The query selecting the last id has been succesfull
				$this->galleryId = $stmt->fetchColumn();
			}
		}
	} // End of the method
	// Define the method updateFiles()
	public function updateFiles($list, $mainImage, $path) {
		// Reference to global variables:
		global $pdo;
		// Create the query to update file data in the MySQL database:
		$q = 'UPDATE galleries SET fileNames=:list, mainImage=:mainImage, path=:path WHERE galleryId=:galleryId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':list' => $list, ':mainImage' => $mainImage, ':path' => $path, ':galleryId' => $this->galleryId));
		if ($r) { // The query has been succesfull
			return TRUE;
		} else {
			return FALSE;
		}
	}
	// Define the metod getData()
	public function getData($galleryId) {
		// Reference to global variables:
		global $pdo;
		// Create the query to retrieve data from MySQL
		$q = 'SELECT * FROM galleries WHERE galleryId=:galleryId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':galleryId' => $galleryId));
		if ($r) {
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($result = $stmt->fetch()) {
				foreach ($result as $key => $value) {
					$this->$key = $value;
				}
			}	
		}
	} // End of getData() method
	// Define the update() method
	public function update($data) {
		// Reference to global variables:
		global $pdo;
		// Create the query that will update all the information in the database:
		$q = 'UPDATE galleries SET title=:title, description=:description, tags=:tags, fileNames=:fileNames, mainImage=:mainImage, public=:public WHERE galleryId=:galleryId';
		$stmt = $pdo->prepare($q);
		$r = $stmt->execute(array(':title' => $data['title'], ':description' => $data['description'], ':tags' => $data['tags'], ':fileNames' => $data['fileNames'], 
		':mainImage' => $data['mainImage'], ':public' => $data['public'], ':galleryId' => $data['galleryId']));
		if ($r) { // The query was succesfully run}
			return TRUE;
		} else { // There was an error
			return FALSE;
		}
	}
}
?>