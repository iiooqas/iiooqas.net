<?php
error_reporting(E_ALL);
require_once("api.php");
require_once("../db/words.php");
require_once("../db/translations.php");

$str = $_POST["string"];
$json = json_decode($str, true);

$wid = $json["wid"];
$datum = $json["date"];
$autor = $json["author"];
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
?>