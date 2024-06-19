<h1>Verify</h1>

Things to check when verifying:
<ul>
    <li>The word is pronouncable and divided into proper syllables
    <li>The word has a length fitting the specificity of the translation
    <li>The word is not extremely similar to another word with a totally different meaning
    <li>No prefix or suffix that has a meaning assigned is used without that meaning (like des-, -art, -fil, -mas)
    <li>Derivations are given when needed
    <li>The translations are spelled properly
    <li>All derivate forms of the translation are present and marked as the correct wordtype
    <li>There are no other words with similar meanings that don't derive in some form
    <li>The translations are not so specific that there wouldn't be a word needed for it (especially for non-derived words)
    <li>Likely no one will get sea cucumbered for anything in here
</ul>

<h2>Words to verify</h2>
<?php
//List all words with verifyStatus = 0
$sql = "SELECT * FROM words WHERE verifyStatus=0;";
$ret = $db->query($sql);
while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
    echo "<div>";
    echo "<h3>".$row['word']."</h3>";
    echo "<p>".$row['comment']."</p>";
    echo "<a href='verify.php?wordid=".$row['id']."'>Verify</a>";
    echo "</div>";
}

<?php
function displayReviewInfoForWord($wordid) {
    global $db;
    $sql = "SELECT * FROM words WHERE id=$wordid;";
    $ret = $db->query($sql);
    $word = $ret->fetchArray(SQLITE3_ASSOC);
    $sql = "SELECT * FROM translations WHERE word=$wordid;";
    $ret = $db->query($sql);
    $translations = [];
    while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $translations[] = $row;
    }
    $sql = "SELECT * FROM derivations WHERE word=$wordid;";
    $ret = $db->query($sql);
    $derivations = [];
    while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $derivations[] = $row;
    }
    echo "<h2>Word: ".$word['word']."</h2>";
    echo "<h3>Translations</h3>";
    echo "<table>";
    echo "<tr><th>Translation</th><th>Language</th><th>Author</th><th>Date</th><th>Wordtype</th><th>Dictionarytype</th><th>Comment</th></tr>";
    foreach($translations as $translation) {
        echo "<tr>";
        echo "<td>".$translation['translation']."</td>";
        echo "<td>".$translation['language']."</td>";
        echo "<td>".$translation['author']."</td>";
        echo "<td>".$translation['date']."</td>";
        echo "<td>".$translation['wordtype']."</td>";
        echo "<td>".$translation['dictionarytype']."</td>";
        echo "<td>".$translation['comment']."</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<h3>Derivations</h3>";
    echo "<table>";
    echo "<tr><th>Derivation</th><th>Language</th><th>Author</th><th>Date</th><th>Wordtype</th><th>Dictionarytype</th><th>Comment</th></tr>";
    foreach($derivations as $derivation) {
        echo "<tr>";
        echo "<td>".$derivation['derivation']."</td>";
        echo "<td>".$derivation['language']."</td>";
        echo "<td>".$derivation['author']."</td>";
        echo "<td>".$derivation['date']."</td>";
        echo "<td>".$derivation['wordtype']."</td>";
        echo "<td>".$derivation['dictionarytype']."</td>";
        echo "<td>".$derivation['comment']."</td>";
        echo "</tr>";
    }
}
?>