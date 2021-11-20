<?php
include 'settings.php';
include 'bloglist.php';

$reverseOrder = array_reverse($blogPost);
 
$getPos = $_POST['nextBatch'] ; //Would be from the get post

$i = 0;

while ($i < $getPos) {
	unset($reverseOrder[$i]); // remove item at index 0
	$i++;
}
	
$reverseOrder2 = array_values($reverseOrder); // 'reindex' arra

///print_r($reverseOrder2);


$numPosts = count($reverseOrder2);

if ($numPosts > $resultsPerScroll) {
	$numPosts = $resultsPerScroll;
}

$i = 0;

while ($i < $numPosts) {
		
	include 'blog/'.$reverseOrder2[$i].'/index.php';

$i++;

}


?>