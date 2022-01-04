<?php
	require_once '../settings.php';
	require_once '../header.php';
	include '../bloglist.php';	


	?>
	<a href="index.php">Return to Main</a><br/><br/>
	<?php


	$numPosts = count($blogPost);
	$i = 0;

	while ($i < $numPosts) {
		
		
		copy("../blog/template.php",'../blog/'.$blogPost[$i].'/index.php');
		
		echo 'Updated: ' . $blogPost[$i] . '<br/>';
		$i++;

	}

	
	//	sleep(3); //Give the file time to update
	
	
