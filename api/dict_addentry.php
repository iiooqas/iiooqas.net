<?php
error_reporting(E_ALL);
require_once("api.php");
require_once("../db/words.php");
require_once("../db/translations.php");

$str = $_POST["string"]; //example:  {"word":"abch","date":"2024-06-11 14:01:08","author":"","translations":[{"translation":"deef","language":"de","dictionarytype":"1","comment":""}],"nsfw":false,"zusammensetzungen":[{"translation":"art","wordtype":"1","comment":"aas"}]}
$json = json_decode($str, true);

$silben = $json["word"];
$wort = str_replace("-", "", $silben);
$pronounciation = $wort;
$datum = $json["date"];
$autor = $json["author"];
$nsfw = $json["nsfw"];
$kommentar = $json["comment"];
$wid = insertWort($wort, $silben, $pronounciation, $datum, $autor, $kommentar, 0, $nsfw);
if($wid == -1) {
    echo "Error inserting word";
    die();
}
echo "wid: $wid";
foreach($json["translations"] as $translation) {
    $word = $wid;
    $language = $translation["language"];
    $translation_ = $translation["translation"];
    $author = $autor;
    $date = $datum;
    $wordtype = $translation["wordtype"];
    $dictionarytype = $translation["dictionarytype"];
    $comment = $translation["comment"];
    add_translation($word, $language, $translation_, $author, $date, $wordtype, $dictionarytype, $comment);
}

foreach($json["zusammensetzungen"] as $zusammensetzung) {
    $word = $wid;
    $translation = $zusammensetzung["translation"];
    $author = $autor;
    $date = $datum;
    $type = $zusammensetzung["type"];
    $comment = $zusammensetzung["comment"];
}
?>