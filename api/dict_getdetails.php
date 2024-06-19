<?php
require_once("api.php");

if(!isset($_GET["id"])) {
	if(isset($_GET["word"])) {
		$ret = $db->query("SELECT id FROM words WHERE word = '".SQLite3::escapeString($_GET["word"])."';");
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
			$id = $row["id"];
		}
	} else {
		die("No ID");
	}
} else {
	$id = $_GET["id"];
}
if(!is_numeric($id)) {
	die("Invalid ID");
}
if(strlen($id) > 8) {
	die("Invalid ID");
}

echo "{";
jsonifyquery("words", "SELECT * FROM words WHERE words.id = $id" . ($ROLE_VIEW_NSFW ? "" : " AND words.isNSFW = 0") . ";");
jsonifyquery("translations", "SELECT * FROM translations WHERE word = $id;");
jsonifyquery("derivations", "SELECT * FROM derivations WHERE word = $id OR derivedfrom = $id;");
jsonifyquery("wortfelder", "SELECT * FROM wortfelder WHERE word = $id;");
jsonifyquery("author", "SELECT id, displayname, iiooname FROM authors WHERE id = (SELECT author FROM words WHERE id = $id);");
echo "}";
?>