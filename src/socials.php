<?php


include 'settings.php';

/* Twitter */
if (strlen($twitter) > 0 ) {
		echo '<a href="https://twitter.com/'.ltrim($twitter, "@").'" target="_blank" aria-label="Follow us on Twitter '.$twitter.'"><i class="fab fa-twitter mt-2 mb-0 ml-1 mr-1"></i></a>';
}

/* Instagram */
if (strlen($instagram) > 0 ) {
		echo '<a href="https://instagram.com/'.$instagram.'" target="_blank" aria-label="Follow us on Instagram"><i class="fab fa-instagram mt-2 mb-0 ml-1 mr-1"></i></a>';
}

/* Facebook */
if (strlen($facebook) > 0 ) {
		echo '<a href="https://facebook.com/'.$facebook.'" target="_blank" aria-label="Follow us on Facebook"><i class="fab fa-facebook mt-2 mb-0 ml-1 mr-1"></i></a>';
}

/* Reddit */
if (strlen($reddit) > 0 ) {
		echo '<a href="https://reddit.com/'.$reddit.'" target="_blank" aria-label="Follow us on Reddit"><i class="fab fa-reddit mt-2 mb-0 ml-1 mr-1"></i></a>';
}
