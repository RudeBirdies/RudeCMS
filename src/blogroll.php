<?php


include 'bloglist.php';

$reverseOrder = array_reverse($blogPost);

$numPosts = count($reverseOrder);

$maxPosts = $numPosts;

if ($numPosts > $initialOnScreen) {
	$numPosts = $initialOnScreen;
}

// How many have we already shown?
if (isset($_GET['page']) && $_GET['page'] <> '0') {
	$startAt = (preg_replace('[\D]', '', $_GET['page']) * $initialOnScreen);
} else {
	$startAt = 0;
}




$i = 0;

while ($i < $numPosts) {
	if ($startAt > count($reverseOrder)) {
		break; // If some wise guy tried putting in a page number higher than our max, then exit.
	}
	
	include 'blog/'.$reverseOrder[$startAt].'/index.php';

	$startAt++;
	$i++;

	if ($startAt >= count($reverseOrder)) {
		break; // If we've now hit our max before finishing out loop, then exit.
	}


}


?>
