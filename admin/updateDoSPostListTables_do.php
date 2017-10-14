<?php
if(basename($_SERVER['SCRIPT_FILENAME']) !== "updateDoSPostListTables_do.php") {
	die("Direct access to this script is forbidden!");
}
$mysqli = new mysqli("localhost", "bidlist_admin", "29Fl3u2KD9iU", "bidlist");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

switch($_POST["formAction"]) {
	case "updatePost":
		$query = "UPDATE postList SET hardship = '".$_POST["posthardship"]."', danger = '".$_POST["postdanger"]."', cola = '".$_POST["postcola"]."' WHERE name = '".$_POST["postName"]."';";
		if (!$result = $mysqli->query($query)) {
			printf("Error: %s\n", $mysqli->error);
		}
		header("Location: https://bidlist.terminus.su/admin/updateDoSPostListTables.php#".$_POST["postName"]."");
		break;
}

$result->free();
$mysqli->close();
?>