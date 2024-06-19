<?php
/*
CREATE TABLE translations (
	id INTEGER PRIMARY KEY,
	word INTEGER,
	language INTEGER,
	translation TEXT,
	author INTEGER,
	date TEXT,
	isvalid INTEGER, /* 0 = not valid, 1 = valid, 2 = valid but not dictionary-relevant *
	wordtype TEXT,
	dictionarytype INTEGER, /* 0 = unknown, 1 = root, 2 = derived from root, 3 = filler nonderived, 4 = filler derived *
	comment TEXT
);
*/

const LANGUAGE_GERMAN = 1;
const LANGUAGE_ENGLISH = 2;
const LANGUAGE_ESPERANTO = 3;
const DICTIONARYTYPE_UNKNOWN = 0;
const DICTIONARYTYPE_ROOT = 1;
const DICTIONARYTYPE_DERIVED = 2;
const DICTIONARYTYPE_FILLER_ROOT = 3;
const DICTIONARYTYPE_FILLER_DERIVED = 4;

function add_translation($word, $language, $translation, $author, $date, $wordtype, $dictionarytype, $comment) {
	global $db;
	//Escape values
	$word = SQLite3::escapeString($word);
	$translation = SQLite3::escapeString($translation);
	$date = SQLite3::escapeString($date);
	$wordtype = SQLite3::escapeString($wordtype);
	$comment = SQLite3::escapeString($comment);
	//Insert values
	$sql = "INSERT INTO translations (word, language, translation, author, date, isvalid, wordtype, dictionarytype, comment) VALUES ('$word', $language, '$translation', $author, '$date', 0, '$wordtype', '$dictionarytype', '$comment');";
	echo $sql;
	$ret = $db->exec($sql);
	if(!$ret) {
		echo $db->lastErrorMsg();
		return false;
	} else {
		echo "<!--Added successfully-->";
	}
	return true;
}
?>