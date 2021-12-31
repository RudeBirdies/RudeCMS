<?php
$getCurPage = basename($_SERVER['PHP_SELF']);
	
	if ($getCurPage == 'template.php') {
		//Don't open this by itself. Bye bye.
		header('Location: ../');
		exit;
	} 
	
if(!function_exists("cleanit")) {
	function cleanit($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  $data = str_replace('lt;', '', $data);
	  $data = str_replace('gt;', '', $data);
	  return $data;
	}
}
	
	$url = $_SERVER['REQUEST_URI'];

	
if (strpos($url,'blog') !== false) {
    $onBlog = '1';
} else {
    $onBlog = '0';
}


if ($onBlog == '1') {
	require_once '../../settings.php';
}

	$mypath=dirname(__FILE__);
	$pieces = explode("/", $mypath);
	$piecesCount = count($pieces);

	$dirName = $pieces[$piecesCount-1];
	$pieces = explode("\\", $dirName); //Adding in split on backslash as this changes depending on host
	$piecesCount = count($pieces);
	$dirName = $pieces[$piecesCount-1];
	
libxml_use_internal_errors(TRUE);

$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
$url = $website."/blog/".$dirName."/".$dirName.".xml";

$xml = file_get_contents($url, false, $context);
$objXmlDocument = simplexml_load_string($xml);


if ($objXmlDocument === FALSE) {
    echo "There were errors parsing the XML file.\n";
    foreach(libxml_get_errors() as $error) {
        echo $error->message;
    }
    exit;
}

$objJsonDocument = json_encode($objXmlDocument);
$arrOutput = json_decode($objJsonDocument, TRUE);

if ($onBlog == '1') {


$addMeta = '
	
	<title>'.$arrOutput['title'].'</title>

	<link rel="canonical" href="'.$website.'/blog/'.$dirName.'/" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="'.$twitter.'" />
	<meta name="twitter:creator" content="'.$twitter.'" />
	<meta name="twitter:title" content="'.cleanit($arrOutput['title']).'" />
	<meta name="twitter:image" content="'.$website.'/blog/'.$dirName.'/'.$dirName.'.jpg" />

	<meta property="og:url" content="'.$website.'/blog/'.$dirName.'/" />
	<meta property="og:title" content="'.cleanit($arrOutput['title']).'" />
	<meta property="og:description" content="'.cleanit($arrOutput['metadesc']).'" />
	<meta property="og:image" content="'.$website.'/blog/'.$dirName.'/'.$dirName.'.jpg" />
	<meta property="og:type" content="article" />
	<meta property="og:updated_time" content="'.$arrOutput['date'].'" />
	<meta property="article:author" content="'.$arrOutput['by'].'" />
	<meta property="article:publisher" content="'.$website.'" />
	
	 <meta name="description" content="'.cleanit($arrOutput['metadesc']).'">


';
}

if ($onBlog == '1') {
	require_once '../../header.php';
}


?>
	
	
	<div class="row p-2">
		<div class="col-12 col-sm-6 offset-sm-3 blogBox p-3">
	
			<div class="col-12 text-center">
				<h3 class="h1"><?php if ($onBlog == '0') { ?><a href="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/"><?php }?><?php echo urldecode($arrOutput['title']);?><?php if ($onBlog == '0') { ?></a><?php } ?></h3>
			</div>
<?php


	if ($onBlog == '1') {
		$filePath = '';
	} else {
		$filePath = 'blog/' . $arrOutput['slug'].'/';
	}

	if (file_exists($filePath.$arrOutput['slug'].'.jpg')) {
?>
			<div class="col-12">
			<picture>
			<?php if ($onBlog == '0') { ?><a href="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/"><?php }?><source srcset="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/<?php echo $arrOutput['slug'];?>.webp?<?php echo $arrOutput['changedate']; ?>" type="image/webp">
			<img class="w-100 mx-auto border border-dark" src="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/<?php echo $arrOutput['slug'];?>.jpg?<?php echo $arrOutput['changedate'];?>" alt="<?php echo cleanit($arrOutput['title']);?>"><?php if ($onBlog == '0') { ?></a><?php } ?>
			</picture>			
			</div>

	<?php 
	}
	
	if ($onBlog == '1') { ?>
			<div class="col-12 mt-2"><small>By: <?php echo $arrOutput['by'];?> | <?php $date = date_create($arrOutput['date']);
			echo date_format($date,"F dS, Y");
			
			?></small></div>
			<div class="col-12"><hr class="m-0  mt-2"></div>
	<?php } ?>


			<div class="col-12 mt-2"><?php if (!empty($arrOutput['body'])) { echo nl2br($arrOutput['body']);} ;?></div>
	<?php if ($onBlog == '1') { 

	if (!empty($arrOutput['tag'])) { 
		$tags  = $arrOutput['tag'];
	} else { 
		$tags ='';
	}

	$pieces = explode(",", $tags);
	
	$numTags = count($pieces);
	$i = 0;

	if ($numTags > 1) {
		?>
			<div class="col-12 mt-2 mb-2">
				<p class="m-0 p-0">&nbsp;</p>
				<p class="m-0 p-0"><small>Tags</small></p>
		<?php 
				
		while ($i < $numTags) {
			echo '<span class="border rounded tagBox text-light p-1 pl-2 pr-2 mr-2 mt-2">'. $pieces[$i] . '</span>';
			$i++;
		}
	?>	</div> <?php 
	
	}

	} ?>
		</div>
	</div>

<?php
if ($onBlog == '1') {
	
/* Navigation */

include '../../bloglist.php';
	
$findSlug = $arrOutput['slug'];
$key = array_search($findSlug, $blogPost); // $key = 2;
$previous = ($key - 1);
$next = ($key + 1);

echo '<div class="col-12 p-0 m-0 text-center">';

if (isset($blogPost[$previous])) {
	echo '<a href="'.$website.'/blog/'.$blogPost[$previous].'/"><button class="border rounded navActive p-1 pl-2 pr-2 mr-2 mt-2">&#171; Previous</button></a>';
} else {
	echo '<button disabled class="border rounded navInactive p-1 pl-2 pr-2 mr-2 mt-2">&#171; Previous</button>';
}

	echo '<a href="'.$website.'"><button class="border rounded navHome p-1 pl-2 pr-2 mr-2 mt-2">Home</button></a>';

if (isset($blogPost[$next])) {
	echo '<a href="'.$website.'/blog/'.$blogPost[$next].'/"><button class="border rounded navActive p-1 pl-2 pr-2 mr-2 mt-2">Next &#187;</button></a>';
} else {
	echo '<button disabled class="border rounded navInactive p-1 pl-2 pr-2 mr-2 mt-2">Next &#187;</button>';
}


echo '</div>';

}
?>


<?php	
if ($onBlog == '1') {
	require_once '../../footer.php';
}
?>
