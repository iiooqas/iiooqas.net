<?php
require_once("api.php");

$search = "";
if(isset($_GET["search"])) {
	$search = $_GET["search"];
}
//replace " and ' with nothing
$search = str_replace("\"", "", $search);
$search = str_replace("'", "", $search);
if(strlen($search) > 42) {
	die("Search term too long");
}
$lang = 1;
if(isset($_GET["lang"])) {
	$lang = $_GET["lang"];
}
if(!is_numeric($lang) {
	$lang = 1;
}

echo "{";
jsonifyquery("words", "SELECT * FROM translations JOIN words ON translations.word=words.id WHERE words.verifyStatus=1 AND (words.word LIKE '%".$search."%' OR translations.translation LIKE '%".$search."%') ORDER BY words.word;");
echo "}";
?>