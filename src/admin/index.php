<?php
	require_once '../settings.php';
	require_once '../header.php';
	
	include '../bloglist.php';
	

	if (isset($_GET['deleted'])) {
		
	/* rebuild the blog list */
	
	$file = '../bloglist.php';
	
	$i = 0; 
	/* rebuild array ids */
	foreach ($blogPost as $value) {
		$postSort[$i] = $value;
		$i++;
	}

	$i = 0; 
	/* rebuild array ids */
	foreach ($blogPostTime as $value) {
		$postSortTime[$i] = $value;
		$i++;
	}
	
	$numPosts = count($postSort);
	

	$i = 0;
	
	$newPost = '<?php
';
	
	while ($i < $numPosts) {
		
		$newPost = $newPost . '$blogPost['.$i.'] = "'.$postSort[$i].'";
$blogPostTime['.$i.'] = "'.$postSortTime[$i].'";
';

		$i++;

	}
	
	file_put_contents($file, $newPost);
	
	
	
	include '../bloglist.php';
	
	}

?>

Create a new Post
<ul>
<li><a href="createnew.php">Create new</a>
</ul>

Update all from Template<br/>
<p class="m-0 w-50"><small>This will update the index.php file in all blog folders to match the template. Useful to run this if the template has changed and you don't want to update all of them manually.</small></p>
<ul>
<li><a href="updatefromtemplate.php">Update Index Files</a>
</ul>


Existing Posts - Click to Edit
<ul>
<?php
	
$reverseOrder = array_reverse($blogPost);

$numPosts = count($reverseOrder);
$i = 0;

while ($i < $numPosts) {
		
	echo '<li><a href="editblog.php?editslug='.$reverseOrder[$i].'">'.$reverseOrder[$i].'</a> <a href="deletepost.php?deleteslug='.$reverseOrder[$i].'"  onclick="return confirm(\'Are you sure you want to delete this post?\')">[Delete]</a><br/>';

$i++;

}

?>

</ul>
