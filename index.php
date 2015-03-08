<?php
ini_set("display_errors",1);
header("Access-Control-Allow-Origin: *");
$host = "us-cdbr-azure-east2-d.cloudapp.net";
$user = "b34779233e0057";
$pwd = "2f7abdff";
$db = "MoBettaAZO1IzF59;";
$conn = null;
try {
    $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(Exception $e){
    die(var_dump($e));
}
if(isset($_GET['id'])) {
    // Connect to database.
    $id = $_GET['id'];
	$sql = "SELECT tweet FROM tweets WHERE id=?";
	$stmt = $conn->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$stmt->execute(array($id));
	$res = $stmt->fetch();
	if(!isset($res['tweet'])) {
			$text = "error!";
		} else {
			$text = $res['tweet'];
		}
	if(isset($_REQUEST['raw'])) {
		// Encode in a JSON Object and print that
		exit(json_encode(array("tweet"=>$text)));
	} else {
		echo "Looks like you don't have the script installed!<br>";
		echo "<a href='https://addons.mozilla.org/en-us/firefox/addon/greasemonkey/'>Click Here</a> to install Greasemonkey for Firefox<br>";
		echo "<a href='https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo?hl=en'>Click Here</a> to install Tampermonkey for Chrome<br>";
		echo "After you have one of these, <a href='MoBettaTwitta.user.js'>Click Here</a> to install the script.<br><br>";
		exit($text);
	}
} else if(isset($_REQUEST['create'])) {
	if(!isset($_REQUEST['tweet']))
		exit("{error:'Missing tweet in create'}");
	// K, we have the tweet
	$sql = "INSERT INTO tweets SET tweet=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($_REQUEST['tweet']));
	$id = $conn->lastInsertId();
	$ret = array(
		"id" => $id
	);
	exit(json_encode($ret));
}