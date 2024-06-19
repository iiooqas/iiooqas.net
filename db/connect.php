<?php
error_reporting(E_ALL);
class MyDB extends SQLite3 {
  function __construct() {
    $this->open(dirname(__FILE__).'/../worterbuch.sqlite');
  }
}
$db = new MyDB();
if(!$db) {
  echo $db->lastErrorMsg();
} else {
  //echo "<!--Opened database successfully-->";
}
?>