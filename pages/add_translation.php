<h1>Add translation</h1>
<?php
$sql = "SELECT * FROM words WHERE EXISTS(SELECT id FROM translations WHERE translations.word = words.id AND language = 1) AND NOT EXISTS(SELECT id FROM translations WHERE translations.word = words.id AND language = 2) ORDER BY random() LIMIT 1;"; //get a random entry that has english but not german
$ret = $db->query($sql);
$wordrow = $ret->fetchArray(SQLITE3_ASSOC);
$sql = "SELECT * FROM translations WHERE translations.word = ".$wordrow["id"].";";
$ret = $db->query($sql);

echo $wordrow["word"]." (".$wordrow["syllables"].")<br/>";

while($translationrow = $ret->fetchArray(SQLITE3_ASSOC)){
	echo $translationrow["translation"]." (".$translationrow["wordtype"].")<br/>";
}
?>
<hr/>
Englisch:
<table id="uebersetzungen">
	<tr>
		<th>Übersetzung
			<button class="button" onclick="addTranslation()">Hinzufügen</button></th>
		<th>Wortart</th>
		<th>Sprache</th>
		<th>Herkunft</th>
		<th>Kommentar</th>
	</tr>
	<tr>
		<td><input class="form-control" type="text" id="uebersetzung0"></td>
		<td><select id="wortart0" class="form-control">
			<optgroup selected>Auswählen
			<option value="n">Nomen
			<option value="v">Verb
			<option value="a">Adjektiv
			<option value="aqal">Adjektiv (Qalitativ gesteigert)
			<option value="aqan">Adjektiv (Qantitativ gesteigert)
			<option value="des">Vollendetes Adjektiv [des]
			<option value="adv">Adverb
			<option value="pr">Präposition
			<option value="np">Nomen-Phrase
			<option value="vp">Verb-Phrase
			<option value="ap">Adjektiv-Phrase
			<option value="p">Sonstige Phrase
			<option value="">Sonstiges
			</select>
		</td>
		<td><select id="sprache0" class="form-control">
			<option value="2">Englisch
			<option value="1">Deutsch
			<option value="3">Esperanto
			</select>
		</td>
		<td><select id="dictionarytype0" class="form-control">
			<option value="1">Stamm
			<option value="2">Stamm (Ableitung)
			<option value="3">Füllwort
			<option value="4">Füllwort (Ableitung)
			</select>
		</td>
		<td><input id="comment0" class="form-control" type="text" placeholder="Kommentar"></td>
	</tr>
</table>
<button class="btn btn-primary" onclick="submit();" id="submit">Submit</button>
<button class="btn btn-secondary" onclick="window.location.reload();return false">Skip</button>

<script>
function addTranslation(value1="", value2=0, value3=1, value4=1, value5=""){
	var table = document.getElementById("uebersetzungen");
	var row = table.insertRow(-1);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	var id = table.rows.length - 1;
	cell1.innerHTML = '<input class="form-control" type="text" placeholder="Text input" id="uebersetzung'+id+'" onkeyup="checkDeWord('+id+');" value="'+value1+'">';
	cell2.innerHTML = '<select id="wortart'+id+'" class="form-control"><optgroup selected>Auswählen<option value="n">Nomen<option value="v">Verb<option value="a">Adjektiv<option value="aqal">Adjektiv (qalitativ steigernd)<option value="aqan">Adjektiv (qantitativ steigernd)<option value="des">Vollendetes Adjektiv [des]<option value="adv">Adverb<option value="pr">Präposition<option value="np">Nomen-Phrase<option value="vp">Verb-Phrase<option value="ap">Adjektiv-Phrase<option value="p">Sonstige Phrase<option value="">Sonstiges</select>';
	cell3.innerHTML = '<select id="sprache'+id+'" class="form-control"><option value="2">Englisch<option value="1">Deutsch<option value="3">Esperanto</select>';
	cell4.innerHTML = '<select id="dictionarytype'+id+'" class="form-control"><option value="1">Stamm<option value="2">Stamm (Ableitung)<option value="3">Füllwort<option value="4">Füllwort (Ableitung)</select>';
	cell5.innerHTML = '<input id="comment'+id+'" class="form-control" type="text" placeholder="Kommentar">';
	cell1.children[0].value = value1;
	cell2.children[0].children[0].value = value2;
	cell3.children[0].children[0].selectedIndex = value3 - 1;
	cell4.children[0].children[0].selectedIndex = value4 - 1;
	cell5.children[0].value = value5;
}

function submit() {
	function pad2(number) {
		return (number < 10 ? '0' : '') + number;
	}
	var result = {};
	var date = new Date();
	var dateString = date.getFullYear() + "-" + pad2(date.getMonth()+1) + "-" + pad2(date.getDate()) + " " + pad2(date.getHours()) + ":" + pad2(date.getMinutes()) + ":" + pad2(date.getSeconds());
	result["wid"] = <?php echo $wordrow["id"];?>;
	result["date"] = dateString;
	result["author"] = "<?php echo $user; ?>";
	result["translations"] = [];
	result["nsfw"] = 0;
	var table = document.getElementById("uebersetzungen");
	for(var i = 1; i < table.rows.length; i++){
		var row = table.rows[i];
		var word = row.cells[0].children[0].value;
		var wortart = row.cells[1].children[0].value;
		var sprache = row.cells[2].children[0].value;
		var dictionarytype = row.cells[3].children[0].value;
		var comment = row.cells[4].children[0].value;
		if(word != "")
			result["translations"].push({"translation":word, "wordtype":wortart, "language":sprache, "dictionarytype":dictionarytype, "comment":comment});
	}
	if(result["translations"].length == 0)
		return alert("nothing entered");
	var string = JSON.stringify(result);
	console.log(string);
	//send the string to the server
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("demo").innerHTML = this.responseText;
			alert("Wurde hinzugefügt!");//alert(string);
			//reload the page
			location.reload();
		}
	};
	xhttp.open("POST", "iiooqas/api/dict_addtranslations.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("string=" + string);
	//disable the submit button
	document.getElementById("submit").disabled = true;
}
</script>