<?php

/*
CREATE TABLE authors (
    id INTEGER PRIMARY KEY,
    uname TEXT,
    passwd TEXT,
    displayname TEXT,
    iiooname TEXT
);
*/

function createAuthor($uname, $passwd, $displayname, $iiooname) {
    global $db;
    //escape values
    $uname = SQLite3::escapeString($uname);
    $passwd = SQLite3::escapeString($passwd);
    $displayname = SQLite3::escapeString($displayname);
    $iiooname = SQLite3::escapeString($iiooname);
    //check uname for uniqueness
    $sql = "SELECT * FROM authors WHERE uname='$uname';";
    $ret = $db->query($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Read successfully-->";
    }
    if($ret->fetchArray(SQLITE3_ASSOC) != null) {
        echo "<p>Username already taken.</p>";
        return;
    }
    //insert
    $sql = "INSERT INTO authors (uname, passwd, displayname, iiooname) VALUES ('$uname', '$passwd', '$displayname', '$iiooname');";
    $ret = $db->exec($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Inserted successfully-->";
    }
}

function verifyCredentials($uname, $passwd) {
    global $db;
    //escape values
    $uname = SQLite3::escapeString($uname);
    $passwd = SQLite3::escapeString($passwd);
    //read
    $sql = "SELECT * FROM authors WHERE uname='$uname' AND passwd='$passwd';";
    $ret = $db->query($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Read successfully-->";
    }
    return $ret->fetchArray(SQLITE3_ASSOC);
}

function getAuthor($id) {
    global $db;
    //escape values
    $id = SQLite3::escapeString($id);
    //read
    $sql = "SELECT * FROM authors WHERE id='$id';";
    $ret = $db->query($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Read successfully-->";
    }
    return $ret->fetchArray(SQLITE3_ASSOC);
}

function unameToId($uname) {
    global $db;
    //escape values
    $uname = SQLite3::escapeString($uname);
    //read
    $sql = "SELECT * FROM authors WHERE uname='$uname';";
    $ret = $db->query($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    } else {
        echo "<!--Read successfully-->";
    }
    return $ret->fetchArray(SQLITE3_ASSOC)['id'];
}