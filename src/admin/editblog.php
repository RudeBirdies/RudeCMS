<?php
	require_once '../settings.php';
	require_once '../header.php';
	
	if (!isset($_GET['editslug'])) {
		header('Location: '.$website.'/'.$adminarea.'/');
		exit;
	}
	
	$updateSuccess = 0;
	$dirName = $_GET['editslug'];
	$now = time();

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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$filename = $_FILES['FileName'];
	$date = $_POST['Date'];
	$by = $_POST['By'];
	$title = $_POST['Title'];
	$slug = $_POST['Slug'];
	$body = $_POST['Body'];
	$metadesc = $_POST['Meta'];
	$tag = $_POST['Tags'];

if (empty($date)) {
	$date = date('Y-m-d',$now);
}

if (empty($by)) {
	$by = 'Anon';
}

if (empty($title)) {
	$title = 'No Title ' . $now;
}

if (empty($slug)) {
	$slug = 'no-title-' . $now;
}

if (empty($body)) {
	/* Replacing "Empty" with an empty string to allow for blogs without content other than pictures.*/
	/* $body = 'Empty'; */
	$body = '';
}

if (empty($metadesc)) {
	$metadesc = 'None Supplied';
}

//print_r($_POST);

	
	if (!empty(basename($_FILES["FileName"]["name"]))) {

		$target_dir = '../blog/'.$slug;
		$target_file = $target_dir .'/'. $slug . '.jpg';

		move_uploaded_file($_FILES["FileName"]["tmp_name"], $target_file);
	
	
		$file=$target_file;
		//$image=  imagecreatefromjpeg($file);

		//Mod to handle PNG file uploads.
		
		// Get the uploaded file extension
		$file_ext = strtolower(pathinfo($_FILES["FileName"]["name"], PATHINFO_EXTENSION));

		// Check if the file is a JPEG or PNG
		if ($file_ext === 'jpg' || $file_ext === 'jpeg') {
			$image = imagecreatefromjpeg($file);
		} elseif ($file_ext === 'png') {
			$image = imagecreatefrompng($file);
		} else {
			// File is not a supported image type
			exit("Error: File must be a JPEG or PNG image");
		}
			
		$width = 500;
		$height = 500;
		$resized_image = imagescale($image, $width, $height);

		
		ob_start();
		imagejpeg($resized_image,NULL,100);
		$cont=  ob_get_contents();
		ob_end_clean();
		
		imagedestroy($image);
		$content =  imagecreatefromstring($cont);
		imagewebp($content,$target_dir .'/'. $slug . '.webp', 95);
		
		//Mod to handle PNG file uploads, convert them to jpg
		imagejpeg($content,$target_dir .'/'. $slug . '.jpg', 95);
		
		imagedestroy($content);
		imagedestroy($resized_image);
	}
	
if ($slug !== $dirName) {
	
	rename('../blog/'.$dirName,'../blog/'.$slug);
	rename('../blog/'.$slug.'/'.$dirName.'.jpg','../blog/'.$slug.'./'.$slug.'.jpg');
	rename('../blog/'.$slug.'/'.$dirName.'.xml','../blog/'.$slug.'./'.$slug.'.xml');

	//Update the blog list.
	$str=file_get_contents('../bloglist.php');
	$str=str_replace($dirName, $slug,$str);
	file_put_contents('../bloglist.php', $str);

	$dirName = $slug;
	
}

/*
if (!file_exists('../blog/'.$slug)) {
    mkdir('../blog/'.$slug, 0777, true);
}
*/
	

	$myfile = fopen('../blog/'.$slug.'/'.$slug.'.xml', "w") or die("Unable to open file!");
	$txt = '<blog>
		<date>'.$date.'</date>
		<by>'.cleanit($by).'</by>
		<title>'.cleanit($title).'</title>
		<slug>'.cleanit($slug).'</slug>
		<body>'.cleanit($body).'</body>
		<metadesc>'.cleanit($metadesc).'</metadesc>
		<tag>'.$tag.'</tag>
		<changedate>'.time().'</changedate>
	</blog>
	';
	fwrite($myfile, $txt);
	fclose($myfile);

	$updateSuccess = 1;
	
	
		sleep(3); //Give the file time to update
	
	include '../bloglist.php';

	$keyToUpdate = array_search($slug, $blogPost);	
	$stringToUpdate = '$blogPostTime['.$keyToUpdate.'] = "'.$blogPostTime[$keyToUpdate].'"';
	$replaceWith = '$blogPostTime['.$keyToUpdate.'] = "'.$now.'"';
	
	$oldMessage = $stringToUpdate;
	$deletedFormat = $replaceWith;

	$str=file_get_contents('../bloglist.php');
	$str=str_replace($oldMessage, $deletedFormat,$str);
	file_put_contents('../bloglist.php', $str, LOCK_EX);
	



}

if ($updateSuccess == 1) {
?>

<div class="alert alert-success" role="alert">
  Post Successfully Updated!
</div>

<?php
}

if (isset($_GET['success'])) {
?>

<div class="alert alert-success" role="alert">
  Post Successfully Added!
</div>

<?php
}

	$filename = "../blog/".$dirName."/".$dirName.".xml";

	if (!file_exists($filename)) {
		//header('Location: '.$website.'/'.$adminarea.'/');
		//exit;
	}


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
	

	

?>

<form action="editblog.php?editslug=<?php echo $dirName;?>" method="POST"  enctype="multipart/form-data">


	<div class="form-group row">
	<?php


	if (file_exists('../blog/' . $arrOutput['slug'].'/'.$arrOutput['slug'].'.jpg')) {
?>
		<div class="col-12 col-sm-6 offset-sm-3">
			<picture>
			<source srcset="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/<?php echo $arrOutput['slug'];?>.webp?<?php echo rand(1,10000000); //helps prevent caching of image }?>" type="image/webp">
			<img class="w-100 mx-auto border border-dark" src="<?php echo $website;?>/blog/<?php echo $arrOutput['slug'];?>/<?php echo $arrOutput['slug'];?>.jpg?<?php echo rand(1,10000000); //helps prevent caching of image }?>" alt="<?php echo $arrOutput['title'];?>">
			</picture>
		</div>
		<?php
		}
		?>
		<div class="custom-file col-12 col-sm-4 offset-sm-4">
		  <input type="file" class="custom-file-input" id="FileName" name="FileName">
		  <label class="custom-file-label" for="FileName">Choose file</label>
		</div>
		<div class="custom-file col-12 col-sm-4 offset-sm-4">
		<span>Image will only update if new image is selected</span>
		</div>
	</div>

	<div class="form-group row">
		<label for="Date" class="col-sm-2 col-form-label">Date:</label>
		<div class="col-sm-10">
			<input type="date" class="form-control" id="Date" name="Date" value="<?php if (!empty($arrOutput['date'])) { echo $arrOutput['date']; } ?>">
		</div>
	</div>
  
  <div class="form-group row">
    <label for="by" class="col-sm-2 col-form-label">By:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="By" name="By" value="<?php if (!empty($arrOutput['by'])) { echo $arrOutput['by']; }?>">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="Title" class="col-sm-2 col-form-label">Title:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Title" name="Title" value="<?php if (!empty($arrOutput['title'])) { echo htmlentities($arrOutput['title']); }?>">
    </div>
  </div>  
  
  	<script>
	
	const blogTitles = [<?php

	$i = 0; 
	/* rebuild array ids */
	foreach ($blogPost as $value) {
		echo '"'.$value,'",';
		$i++;
	}


?>];

		var slug = function(str) {
		  str = str.replace(/^\s+|\s+$/g, ''); // trim
		  str = str.toLowerCase();

		  // remove accents, swap ñ for n, etc
		  var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
		  var to   = "aaaaaeeeeeiiiiooooouuuunc------";
		  for (var i = 0, l = from.length; i < l; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		  }

		  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
				   .replace(/\s+/g, '-') // collapse whitespace and replace by -
				   .replace(/-+/g, '-'); // collapse dashes

			if ( blogTitles.includes(str) ) {
				$("#Slug").addClass("bg-danger");
			} else {
				$("#Slug").removeClass("bg-danger");
			};
			
		  return str;
		};


		$(document).ready(function() {
		 $("#Title").keyup(function(){
			//slug($('#Slug').val())
			
			$('#Slug').val(slug($('#Title').val()));

			
		 });
		});


	</script>
	
	
   <div class="form-group row">
    <label for="Slug" class="col-sm-2 col-form-label">Slug:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Slug" name="Slug" readonly value="<?php if (!empty($arrOutput['slug'])) { echo $arrOutput['slug']; }?>">
    </div>
  </div>  
  
  <div class="form-group row">
    <label for="Body" class="col-sm-2 col-form-label">Body:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="Body" name="Body" rows="8"><?php if (!empty($arrOutput['body'])) { echo htmlentities($arrOutput['body']); }?></textarea>
    </div>
  </div>  
  
   <div class="form-group row">
    <label for="Meta" class="col-sm-2 col-form-label">Meta Text:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="Meta" name="Meta" rows="4"><?php if (!empty($arrOutput['metadesc'])) { echo $arrOutput['metadesc']; }?></textarea>
    </div>
  </div> 

  <div class="form-group row">
    <label for="Tags" class="col-sm-2 col-form-label">Tags:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Tags" name="Tags" value="<?php if (!empty($arrOutput['tag'])) { echo $arrOutput['tag']; }?>">
	  <span>Separate tags using commas</span>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-sm-12">
      <a href="<?php echo $website;?>/<?php echo $adminarea;?>"><div class="btn btn-danger float-right m-2 ">Cancel Changes</div></a>
	  
	  <button type="submit" class="btn btn-primary float-right m-2 ">Save Changes</button>
	  
    </div>
  </div>
</form>

