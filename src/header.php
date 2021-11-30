<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=M+PLUS+2:wght@400;600&display=swap" rel="stylesheet">

<?php
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
	
if (isset($pageName)) {
	if ($pageName == 'homepage') {
	echo '<link rel="canonical" href="'.$website.'/" />';
	echo '<meta name="description" content="'.cleanit($blogSubtitle).'">';

	}
}
	
$url = $_SERVER['REQUEST_URI'];
if (strpos($url,'blog') !== false) {
    $onBlog = '1';
} else {
    $onBlog = '0';
}

if (strpos($url,$adminarea) !== false) {
    $onAdmin = '1';
} else {
    $onAdmin = '0';
}

$getCurPage = basename($_SERVER['PHP_SELF']);
	
if ($getCurPage == '404.php') {
	$on404 = '1';
} else {
	$on404 = '0';
}
	

if (isset($addMeta)) {
	echo $addMeta;
} else {
?><title><?php echo $blogName . ' | ' . $blogSubtitle;?></title>
	
<?php
}
?>

<style>
body {
font-family: 'M+PLUS+2', sans-serif;
  font-weight: 400;
  font-size: 1em;
}

h1{
	font-family: 'M+PLUS+2', sans-serif;	
	font-weight: 600;
	color: <?php echo $headingColor;?>;
}

h2{
	color: <?php echo $subheadingColor;?>;
}

h3 {
	font-family: 'M+PLUS+2', sans-serif;	
	font-weight: 600;
	color: <?php echo $headingColor;?>;	
}

	
span {
display: inline-block;
font-size: 0.8em;
}

.tagBox {
	background-color: <?php echo $tagBoxColor;?>;
	color: <?php echo $tagBoxTextColor;?>;
}

.navActive {
	background-color: <?php echo $navActive;?>;
	color: <?php echo $navActiveText;?>;
	width:100%;
	max-width:110px;
}

.navHome {
	background-color: <?php echo $navActive;?>;
	color: <?php echo $navActiveText;?>;
	width:100%;
	max-width:70px;
}

.navInactive {
	background-color: <?php echo $navInactive;?>;
	color: <?php echo $navInactiveText;?>;
	width:100%;
	max-width:110px;
}



.blogBox {
	border: 1px solid <?php echo $postBorderColor;?>;
	background-color: <?php echo $postBackgroundColor;?>;
}



a:link {
  text-decoration: none;
  color: <?php echo $linkColor;?>;
}

a:visited {
  text-decoration: none;
  color: <?php echo $linkVisitedColor;?>;
}

a:hover {
  text-decoration: none;
  color: <?php echo $linkHoverColor;?>;
}

a:active {
  text-decoration: none;
  color: <?php echo $linkActiveColor;?>;
  }



</style>
</head>

<body>
<div class="container-sm">
	<div class="row p-2">
		<div class="col-12 text-center">
			<a href="<?php echo $website;?>"><img src="<?php echo $website;?>/logo.png" class="d-block mx-auto rounded-circle border border-dark m-2" alt="<?php echo $blogName;?> Logo"></a>
			<h1><?php echo $blogName;?></h1>
			<h2 class="h6"><?php echo $blogSubtitle;?></h2>
			<?php include 'socials.php';?>
		</div>
	
		<div class="col-12">
			&nbsp;
		</div>
	</div>

<?php
if ($onBlog + $onAdmin +$on404 == 0) {
?>
	<div class="row p-2">
		<div class="col-12 col-sm-6 offset-sm-3 p-0">
			<?php
			
			echo nl2br($blogHomeText);
			
			?>
		</div>
	
		<div class="col-12">
			&nbsp;
		</div>
	</div>
<?php
}
?>
