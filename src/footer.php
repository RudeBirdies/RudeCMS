
<?php
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