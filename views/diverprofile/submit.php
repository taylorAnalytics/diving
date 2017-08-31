<?php
/* This script will submit the article in the database. It will:
 * - run the submit() method of the article object
 * - show the comment if the submission was successfull
 * - show the links to writing a new article or managing all your articles
 */

$article = $_SESSION['article'];
$article->save();
$article->submit();
 

?>