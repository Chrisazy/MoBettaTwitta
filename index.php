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

		echo '<!DOCTYPE html>
				<html lang="en">
 				 <head>
 				   <meta charset="utf-8">
 			   <meta http-equiv="X-UA-Compatible" content="IE=edge">
 			   <meta name="viewport" content="width=device-width, initial-scale=1">
  			  <title>MoBettaTwitta</title>';
		echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">';
		echo '<link rel="stylesheet" href="https://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css">';
		echo '</head>';
		echo '<body>';
		echo '<div class="container">';
		?>
		<div class="header">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="https://addons.mozilla.org/en-us/firefox/addon/greasemonkey/">Greasemonkey</a></li>
            <li role="presentation"><a href="https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo?hl=en">Tampermonkey</a></li>
            <li role="presentation" class="active"><a href="MoBettaTwitta.user.js">Userscript</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">MoBettaTwitta</h3>
      </div>

		<?php
		echo "<div class='jumbotron'>";
		echo "<h1>MoBetta Tweet</h1>";
		echo '<p class="lead">';
		echo $text;
		echo '</p>';
		echo '</div>';
		echo "</div>";
		echo '</body></html>';
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