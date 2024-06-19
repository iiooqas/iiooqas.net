<?php
session_start();
require_once "../db/connect.php";

header('Content-Type: application/json');

$user = 0;
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}

$roles = array();
if($user != 0) {
	$sql = "SELECT * FROM authorroles WHERE author = $user;";
	$ret = $db->query($sql);
	while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
		$roles[$row["rolle"]] = true;
	}
}

$ROLE_ADD_WORD_TRANSLATION_DERIVATION = isset($roles[1]);
$ROLE_EDIT_WORD = isset($roles[2]);
$ROLE_EDIT_TRANSLATION = isset($roles[3]);
$ROLE_DELETE_WORD = isset($roles[4]);
$ROLE_DELETE_TRANSLATION = isset($roles[5]);
$ROLE_DISABLE_ACCOUNT = isset($roles[6]);
$ROLE_WORTFELD = isset($roles[7]);
$ROLE_VERIFY = isset($roles[8]);
$ROLE_VIEW_NSFW = isset($roles[9]);
$ROLE_VIEW_INVALID = isset($roles[10]);
$ROLE_VIEW_HISTORY = isset($roles[11]);

$firstjsonifquery = true;
function jsonifyquery($jsonkey, $sql){
	global $db;
	global $firstjsonifquery;
	$ret = $db->query($sql);
	if($firstjsonifquery) {
		$firstjsonifquery = false;
	} else {
		echo ",";
	}
	echo "\"".$jsonkey."\":[";
	$first = true;
	while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
		if($first) {
			$first = false;
		} else {
			echo ",";
		}
		echo json_encode($row);
	}
	echo "]";
}