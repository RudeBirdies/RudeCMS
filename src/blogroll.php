<?php


include 'bloglist.php';

$reverseOrder = array_reverse($blogPost);

$numPosts = count($reverseOrder);
$maxPosts = $numPosts;

if ($numPosts > $initialOnScreen) {
	$numPosts = $initialOnScreen;
}

$i = 0;

while ($i < $numPosts) {
		
	include 'blog/'.$reverseOrder[$i].'/index.php';

$i++;

}


?>
