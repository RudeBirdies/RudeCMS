<?php
	require_once '../settings.php';
	require_once '../header.php';
	
	/*
	if (!isset($_GET['editslug'])) {
		header('Location: '.$website.'/'.$adminarea.'/');
		exit;
	}
	*/

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
	$body = 'Empty';
}

if (empty($metadesc)) {
	$metadesc = 'None Supplied';
}

if (!file_exists('../blog/'.$slug)) {
    mkdir('../blog/'.$slug, 0777, true);
}

	if (!empty(basename($_FILES["FileName"]["name"]))) {

		$target_dir = '../blog/'.$slug;
		$target_file = $target_dir .'/'. $slug . '.jpg';

		move_uploaded_file($_FILES["FileName"]["tmp_name"], $target_file);

	}
	
	$txt = '';
	$myfile = fopen('../blog/'.$slug.'/'.$slug.'.xml', "w") or die("Unable to open file!");
	
	$txt = '<blog>
		<date>'.$date.'</date>
		<by>'.cleanit($by).'</by>
		<title>'.cleanit($title).'</title>
		<slug>'.cleanit($slug).'</slug>
		<body>'.cleanit($body).'</body>
		<metadesc>'.cleanit($metadesc).'</metadesc>
		<tag>'.$tag.'</tag>
		<changedate>'.$now.'</changedate>
	</blog>
	';
	fwrite($myfile, $txt);
	fclose($myfile);

	copy("../blog/template.php",'../blog/'.$slug.'/index.php');

	include '../bloglist.php';
	$numPosts = count($blogPost);
	
	$file = '../bloglist.php';
	$newPost = '$blogPost['.$numPosts.'] = "'.$slug.'";
$blogPostTime['.$numPosts.'] = "'.$now.'";
'; //force another line because I can't remember right now
	
	file_put_contents($file, $newPost, FILE_APPEND | LOCK_EX);

	header('Location: '.$website.'/'.$adminarea.'/editblog.php?editslug='.$slug.'&success');
	exit;

}

?>

<form action="createnew.php" method="POST"  enctype="multipart/form-data">
<script>
function previewImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imgPrv')
                    .attr('src', e.target.result)
                    .width(500)
					.addClass( "d-block mx-auto mb-2" );
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<img id="imgPrv" src="#" alt="Image Preview" class="d-none"/>

	<div class="form-group row">
		<div class="custom-file col-12 col-sm-4 offset-sm-4">
		  <input type="file" class="custom-file-input" id="FileName" name="FileName" onchange="previewImg(this);">
		  <label class="custom-file-label" for="FileName">Choose file</label>
		</div>
		<div class="custom-file col-12 col-sm-4 offset-sm-4">
		</div>
	</div>

			
	<div class="form-group row">
		<label for="Date" class="col-sm-2 col-form-label">Date:</label>
		<div class="col-sm-10">
			<input type="date" class="form-control" id="Date" name="Date" value="<?php
			
			$now = time();
			
			echo date('Y-m-d',$now);


			
			?>">
		</div>
	</div>
  
  <div class="form-group row">
    <label for="by" class="col-sm-2 col-form-label">By:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="By" name="By" placeholder="Your Name">
    </div>
  </div>
  
  <div class="form-group row">
    <label for="Title" class="col-sm-2 col-form-label">Title:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Title" name="Title" placeholder="Awesome Post Title">
    </div>
  </div>  
  
  	<script>
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
      <input type="text" class="form-control" id="Slug" name="Slug" readonly value="">
    </div>
  </div>  
  
  <div class="form-group row">
    <label for="Body" class="col-sm-2 col-form-label">Body:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="Body" name="Body" rows="8"></textarea>
    </div>
  </div>  
  
   <div class="form-group row">
    <label for="Meta" class="col-sm-2 col-form-label">Meta Text:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="Meta" name="Meta" rows="4" placeolder="test"></textarea>
    </div>
  </div> 

  <div class="form-group row">
    <label for="Tags" class="col-sm-2 col-form-label">Tags:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Tags" name="Tags" placeholder="This, That, Something Else">
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

