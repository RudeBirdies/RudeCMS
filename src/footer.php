
<?php

if ($usePagination == 0) { // Do Infinite Scroll
	if ($onBlog == '0') {
	?>

	<div class="col-12 p-0" id="blogparts"></div>

	<script> 

	var nextBatch = <?php echo $initialOnScreen;?>;	
	var loading = 0;
	var maxOut = <?php echo $maxPosts;?>;

	$(window).scroll(function() {
	   if($(window).scrollTop() + $(window).height() > $(document).height() - 150 ) {

				loading = loading + 1;
			
				if (loading == 1) {
					if (nextBatch <= maxOut) {

						$.post("getmore", { 
								nextBatch:nextBatch,
								async: false,
								}, function(result) {

								$('#blogparts').append(result);
								

						});
					};
				
				setTimeout(resetLoading, 500); // give us time to load the new blogs
				} 
		}
	});


	function resetLoading() {
			loading = 0;
			nextBatch = nextBatch + <?php echo $resultsPerScroll;?>;		
	}

	</script>

<?php
	} //End if on blog
	
} else {	// Do Pagination

if (isset($_GET['page'])) {
	$getPage = preg_replace('[\D]', '', $_GET['page']);
} else {
	$getPage = 0;
}

if (($getPage * $initialOnScreen) <= count($reverseOrder)) {  // If some wise guy tried putting in a page number higher than our max, this will skip our pagination

	echo '	<div class="row">
			<div class="col-12 text-center">
				<p class="m-0 p-0">&nbsp;</p>';

	if ($getPage == 0) {
		echo '<p class="d-inline-block text-secondary"><i class="fa-solid fa-chevron-left"></i> Back</p>';
	} else {
		echo '<a href="'.$website.'?page=' . ($getPage-1).'"><i class="fa-solid fa-chevron-left"></i> Back</a>';
	}

	echo ' | ';

	if ($startAt >= count($reverseOrder)) {
		echo '<p class="d-inline-block text-secondary">Next <i class="fa-solid fa-chevron-right"></i></p>';
	} else {
		echo '<a href="'.$website.'?page=' . ($getPage+1).'">Next <i class="fa-solid fa-chevron-right"></i></a>';
	}

	echo '</div>
	</div>';
}

}
?>

	<div class="row">
		<div class="col-12">
			<p class="m-0 p-0">&nbsp;</p>
		</div>
			
		<div class="col-12 text-center" id="footerArea">
			<?php include 'socials.php';?>

			<p class="mt-2 p-0"><small><?php echo $footerCopyright;?></small></p>
			<p class="mb-3 p-0"><small>Powered by <a href="https://rudecms.com">RudeCMS</a></small></p>
		</div>
	</div>
</div>			

</body>
</html>
