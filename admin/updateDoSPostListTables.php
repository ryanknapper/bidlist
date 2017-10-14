<?php
$mysqli = new mysqli("localhost", "bidlist_admin", "29Fl3u2KD9iU", "bidlist");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
if (!$result = $mysqli->query("SELECT name, hardship, danger, cola FROM postList ORDER BY name ASC")) {
	printf("Error: %s\n", $mysqli->error);
}
$dbAll = $result->fetch_all(MYSQLI_ASSOC);

$result->free();
$mysqli->close();
foreach($dbAll as $dbRow) { 
	$dbList[$dbRow["name"]]["hardship"] = $dbRow["hardship"];
	$dbList[$dbRow["name"]]["danger"] = $dbRow["danger"];
	$dbList[$dbRow["name"]]["cola"] = $dbRow["cola"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://bidlist.terminus.su/css/bootstrap.min.css">
	<script src="https://bidlist.terminus.su/js/bootstrap.min.js"></script>
</head>
<body>
<div style="text-align: center;">
<table class="table table-sm table-striped table-hover" style="width: 500px;">
<tr><thead class="thead-inverse"><th>Post</th><th>Hardship</th><th>Danger</th><th>COLA</th><th>&nbsp;</th></thead></tr>
<?php
// Compile post allowance list from local files
$allowanceList = array("hardship", "danger", "cola");
foreach($allowanceList as $name) {
	$filePath = "/var/www/bidlist/tmp/dos-$name.list";
	$handle = fopen($filePath, "r");
	$contents = fread($handle, filesize($filePath));
	fclose($handle);
	foreach(explode(PHP_EOL,$contents) as $line) {
		if($line) {
			$tmp=explode(";",$line);
			$postList[$tmp[0]]["$name"] .= $tmp[1];
		}
	}
}
ksort($postList);

// Display post allowance list in a table
foreach($postList as $postName => $allowance) {
	foreach($allowanceList as $allowanceName) if(!$allowance["$allowanceName"]) $allowance["$allowanceName"] = 0;
	$linkPostName = urlencode($postName);
	echo("<form action='updateDoSPostListTables_do.php' method='post'>\n<input type='hidden' name='formAction' value='updatePost'>\n");
	echo("<tr id='$linkPostName'><th scope='row'><input type='hidden' name='postName' value='$linkPostName' />$postName</th>\n");
	foreach($allowanceList as $name) {
		if($dbList[$linkPostName][$name] != $allowance[$name]) {
			$tdClass = "table-danger";
		} else {
			$tdClass = "";
		}
		echo("\t<td class='$tdClass'><div class='form-group'><input class='form-control' type='number' name='post$name' value='".$allowance[$name]."' /></div></td>\n");
	}
	echo("\t<td><button type='submit' class='btn btn-primary'>Update</button></td>\n");
	echo("</tr>\n</form>\n");
}
?>
</table>
</div>
</body>
</html>
