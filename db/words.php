<?php
/*

CREATE TABLE words (
    id INTEGER PRIMARY KEY,
    word TEXT,
    syllables TEXT,
    stress TEXT,
    date TEXT,
    author INTEGER,
    comment TEXT,
    verifyStatus INTEGER, // 0 = not verified, 1, 2, ... = #people verified, -1 = rejected
    isNSFW INTEGER
);

CREATE TABLE history (
    id INTEGER PRIMARY KEY,
    word INTEGER,
    name TEXT,
    author INTEGER,
    date TEXT
);

*/

function insertWort($wort, $silben, $pronounciation, $datum, $autor, $kommentar, $status, $nsfw) {
    global $db;
    //escape values
    $wort = SQLite3::escapeString($wort);
    $silben = SQLite3::escapeString($silben);
    $betonpronounciationung = SQLite3::escapeString($pronounciation);
    $datum = SQLite3::escapeString($datum);
    $autor = SQLite3::escapeString($autor);
    $kommentar = SQLite3::escapeString($kommentar);
    $status = SQLite3::escapeString($status);
    $nsfw = SQLite3::escapeString($nsfw);
    //insert
    $sql = "INSERT INTO words (word, syllables, pronounciation, date, author, comment, verifyStatus, isNSFW) VALUES ('$wort', '$silben', '$pronounciation', '$datum', '$autor', '$kommentar', '$status', '$nsfw');";
    $ret = $db->exec($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
        return -1;
    } else {
        echo "<!--Inserted successfully-->";
        //return the id of the inserted word
        return $db->lastInsertRowID();
    }
}

function modifyWord($wordid, $newword, $newpronounciation, $newstress, $newdate, $newauthor, $newcomment, $newstatus, $newnsfw) {
    global $db;
    //escape values
    $newword = SQLite3::escapeString($newword);
    $newsyllables = SQLite3::escapeString($newsyllables);
    $newpronounciation = SQLite3::escapeString($newpronounciation);
    $newdate = SQLite3::escapeString($newdate);
    $newauthor = SQLite3::escapeString($newauthor);
    $newcomment = SQLite3::escapeString($newcomment);
    $newstatus = SQLite3::escapeString($newstatus);
    $newnsfw = SQLite3::escapeString($newnsfw);
    //read old values
    $sql = "SELECT * FROM words WHERE id='$wordid';";
    $ret = $db->query($sql);
    //update history
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $sql = "INSERT INTO history (word, name, author, date) VALUES ('$wordid', '".$row['word']."', '".$row['author']."', '".$row['date']."');";
    $ret = $db->exec($sql);
    //update word
    $sql = "UPDATE words SET word='$newword', syllables='$newsyllables', pronounciation='$newpronounciation', date='$newdate', author='$newauthor', comment='$newcomment', verifyStatus='$newstatus', isNSFW='$newnsfw' WHERE id='$wordid';";
    $ret = $db->exec($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Modified successfully-->";
    }
}
?>