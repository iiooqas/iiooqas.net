<?php
/*
CREATE TABLE examplesentences (
	id INTEGER NOT NULL PRIMARY KEY,
	sentence TEXT,
	author INTEGER,
	date TEXT
);

CREATE TABLE examplesentenceswords (
	id INTEGER NOT NULL PRIMARY KEY,
	sentence INTEGER NOT NULL,
	word INTEGER NOT NULL
):
*/

function addExampleSentence($sentence, $author, $date, $wordids) {
    global $db;
    $sql = "";
    $db->query($sql);
}

?>