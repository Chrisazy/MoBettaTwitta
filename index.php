<?php
if(!isset($_GET['id'])) {

} else {
	// We have an id!
	if(isset($_REQUEST['json'])) {
		// Don't just display it, noob.
	} else {
		// They just cicked the link...Dang.
		echo "Well, here's the link for ".$_GET['id'];
		echo "<br>";
	}
}


?>