<?php
if(!isset($_GET['id'])) {
	// No id
} else {
	$host = "us-cdbr-azure-east2-d.cloudapp.net";
    $user = "b34779233e0057";
    $pwd = "2f7abdff";
    $db = "MoBettaAZO1IzF59;";
    // Connect to database.
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(var_dump($e));
    }
	$text = getTweet($_GET['id']);
	if(isset($_REQUEST['raw'])) {
		// Encode in a JSON Object and print that
	} else {
		// They just cicked the link...Dang.
		echo "Well, here's the link for ".$_GET['id'];
		echo "<br>";
	}
}
?>