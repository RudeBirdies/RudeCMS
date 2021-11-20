<?php
	require_once '../settings.php';
	require_once '../header.php';
	
	if (!isset($_GET['deleteslug'])) {
		header('Location: '.$website.'/'.$adminarea.'/');
		exit;
	}
	
	$dirName = $_GET['deleteslug'];

function cleanit($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = str_replace('lt;', '', $data);
  $data = str_replace('gt;', '', $data);
  return $data;
}


$files = glob('../blog/'.$dirName.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file)) {
			unlink($file); // delete file
		}
	}

rmdir('../blog/'.$dirName);

	include '../bloglist.php';
	$numPosts = count($blogPost);
	
	$keyToRemove = array_search($dirName, $blogPost);

	$stringToRemove = '$blogPost['.$keyToRemove.']';
	$replaceWith = '//'.$stringToRemove;
	
	$oldMessage = $stringToRemove;
	$deletedFormat = $replaceWith;

	$str=file_get_contents('../bloglist.php');
	$str=str_replace($oldMessage, $deletedFormat,$str);
	file_put_contents('../bloglist.php', $str, LOCK_EX);
	
	sleep(3); //Give the file time to update

header('Location: '.$website.'/'.$adminarea.'/?deleted');
		exit;





?>