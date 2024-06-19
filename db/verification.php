<?php
/*
CREATE TABLE verification (
	id INTEGER PRIMARY KEY,
	word INTEGER,
	verifier INTEGER,
	result INTEGER, /* 1 = accepted, 2 = needs minor changes, 3 = needs major changes, 4 = rejected *
	date TEXT,
	needsreevaluation INTEGER,
	comment TEXT
);
*/
const VERIFICATION_ACCEPTED = 1;
const VERIFICATION_MINOR_CHANGES = 2;
const VERIFICATION_MAJOR_CHANGES = 3;
const VERIFICATION_REJECTED = 4;

function addVerification($wordid, $verifier, $result, $comment) {
	global $db;
	//Prepend the commend with the result and date
	$comment = "[".date('Y-m-d H:i:s')." rated ".$result."]\n".$comment;
	$result = $db->escapeString($result);
	$comment = $db->escapeString($comment);
	$sql = "INSERT INTO verification (word, verifier, result, date, needsreevaluation, comment) VALUES ($wordid, $verifier, $result, '".date('Y-m-d H:i:s')."', 0, '$comment');";
	$db->exec($sql);
}

function wordChangedReevaluate($wordid) {
	global $db;
	$sql = "UPDATE verification SET needsreevaluation=1 WHERE word=$wordid;";
	$db->exec($sql);
}

function updateVerification($verificationid, $result, $comment) {
	global $db;
	//Prepend the commend with the result and date
	$comment = "[".date('Y-m-d H:i:s')." rated ".$result."]\n".$comment;
	$result = $db->escapeString($result);
	$comment = $db->escapeString($comment);
	$sql = "UPDATE verification SET result=$result, date='".date('Y-m-d H:i:s')."', needsreevaluation=0, comment='$comment' WHERE id=$verificationid;";
	$db->exec($sql);
	checkVerification($wordid);
}

function checkVerification($wordid) {
	//if all verifications are accepted and there are at least 2 verifications, set the word to verified
	global $db;
	$sql = "SELECT COUNT(*) FROM verification WHERE word=$wordid AND result = ".VERIFICATION_ACCEPTED.";";
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);
	if($row['COUNT(*)'] > 0) {
		$sql = "UPDATE words SET verifyStatus=".$row['COUNT(*)']." WHERE id=$wordid;";
		$db->exec($sql);
		return;
	}
}
?>