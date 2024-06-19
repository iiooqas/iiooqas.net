<?php
require_once "../db/connect.php";
$sql = "SELECT * FROM translations JOIN words ON translations.word=words.id WHERE words.verifyStatus=1;";
$ret = $db->query($sql);
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    echo $row['word']."\t\t\t".$row['translation']."\t".$row['date']."\t".$row['author']."\t".$row['comment']."\n";
}
?>