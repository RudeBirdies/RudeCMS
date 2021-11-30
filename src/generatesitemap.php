<?php
include 'settings.php';
include 'bloglist.php';

$var = '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
 	<url>
		<loc>'.$website.'</loc>
		<lastmod>' . date("Y-m-d",time()). '</lastmod>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
	';

$reverseOrder = array_reverse($blogPost);
$reverseOrderTime = array_reverse($blogPostTime);

$numPosts = count($reverseOrder);
$maxPosts = $numPosts;

$i = 0;

while ($i < $numPosts) {
		
		
$var = $var . '<url>
	<loc>'.$website.'/blog/' . $reverseOrder[$i] . '/</loc>
	<lastmod>' . date("Y-m-d",$reverseOrderTime[$i]). '</lastmod>
	</url>
';

$i++;

}


	
$var = $var . '</urlset>';

		$my_file = 'sitemap.xml';
		$handle = fopen($my_file, 'w') or die('Cannot open file: '.$my_file);

		fwrite($handle, $var);
		
?>


Sitemaps Generated!
