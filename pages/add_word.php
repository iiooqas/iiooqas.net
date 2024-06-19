<?php
//get all words in a proper format

//select all from words join translations on words.word = translations.word
$sql = "SELECT *, words.author as wauthor FROM words JOIN translations ON words.id = translations.word;";
$ret = $db->query($sql);
$words = [];
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	$words[] = $row;
}
//group by word
$words = array_reduce($words, function($carry, $item){
	$carry[$item["word"]][] = $item;
	return $carry;
}, []);

//print as json array containing an object for each word
echo "<script>var words = Object.values(".json_encode($words).").sort((a,b)=>a[0].syllables.replace(\"'\",\"\").localeCompare(b[0].syllables.replace(\"'\",\"\")));</script>";
?>
<div class="row">
	<div class="col-3">
	<div id="intdictionary" style="overflow:scroll; height: calc(100vh - 56px);"></div>
	</div>
	<div class="col-9">
		<h1>Add word</h1>

		<p>Here you can add a word to the dictionary. Please make sure that the word is not already in the dictionary and that the translation is correct.</p>

		<div class="row">
			<div class="col-9">
				<input type="text" id="iiooqisch" placeholder="Word iiooqas" class="form-control" onkeyup="checkIiooWord();"/>
			</div>
			<div class="col-3">
				<input type="checkbox" id="nsfw" class="form-check-input" />
				<label for="nsfw" class="form-check-label">NSFW</label>
			</div>
		</div>
		
		<div id="errormsg"></div>
	
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
				<td><input class="form-control" type="text" id="uebersetzung0" onkeyup="checkDeWord(0);"></td>
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
					<option value="de">Deutsch
					<option value="en">Englisch
					<option value="eo">Esperanto
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

		<table id="zusammensetzungen">
			<tr>
				<th>Zusammensetzung
					<button class="button" onclick="addZusammensetzung()">Hinzufügen</button></th>
				<th>Typ</th>
				<th>Kommentar</th>
			</tr>
			<!--<tr>
				<td><input class="form-control" type="text" id="zusammensetzung0" placeholder="iioo word" onkeyup="checkZusammensetzung(0);"></td>
				<td><select id="zusammensetzungtype0" class="form-control">
					<option value="1">Direkte Kopie
					<option value="2">Ähnlich
					<option value="3">Lose basiert
					<option value="4">Sonstiges
					</select>
				</td>
				<td><input id="zusammensetzungcomment0" class="form-control" type="text" placeholder="Kommentar"></td>
			</tr>-->
		</table>

		<br/>Kommentar: 
		<input type="text" id="globalerkommentar" class="form-control" placeholder="Kommentar"/><br/>
	
		<div class="field is-grouped">
			<div class="control">
				<button class="button btn btn-primary btn-lg" onclick="submit();" id="submit">Submit</button>
			</div>
		</div>

	</div>
</div>
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
	cell3.innerHTML = '<select id="sprache'+id+'" class="form-control"><option value="1">Deutsch<option value="2">Englisch<option value="3">Esperanto</select>';
	cell4.innerHTML = '<select id="dictionarytype'+id+'" class="form-control"><option value="1">Stamm<option value="2">Stamm (Ableitung)<option value="3">Füllwort<option value="4">Füllwort (Ableitung)</select>';
	cell5.innerHTML = '<input id="comment'+id+'" class="form-control" type="text" placeholder="Kommentar">';
	cell1.children[0].value = value1;
	cell2.children[0].children[0].value = value2;
	cell3.children[0].children[0].selectedIndex = value3 - 1;
	cell4.children[0].children[0].selectedIndex = value4 - 1;
	cell5.children[0].value = value5;
}

function addZusammensetzung(value1="", value2=0, value3=""){
	var table = document.getElementById("zusammensetzungen");
	var row = table.insertRow(-1);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var id = table.rows.length - 1;
	cell1.innerHTML = '<input class="form-control" type="text" placeholder="Text input" id="zusammensetzung'+id+'" value="'+value1+'" onkeyup="checkZusammensetzung('+id+');">';
	cell2.innerHTML = '<select id="zusammensetzungtype'+id+'" class="form-control"><option value="1">Direkte Kopie<option value="2">Ähnlich<option value="3">Lose basiert<option value="4">Sonstiges</select>';
	cell3.innerHTML = '<input id="zusammensetzungcomment'+id+'" class="form-control" type="text" placeholder="Kommentar">';
	cell1.children[0].value = value1;
	cell2.children[0].children[0].value = value2;
	cell3.children[0].value = value3;
}

function checkZusammensetzung(id){
	var word = document.getElementById("zusammensetzung"+id).value;
	if(word.length == 0){
		return;
	}
	var good = true;
	var reason = "";
	// word must be in the iioo worterbuch
	var found = false;
	for(var i = 0; i < words.length; i++){
		if(words[i][0].syllables.split("-").join("") == word){
			found = true;
			break;
		}
	}

	if(!found){
		document.getElementById("zusammensetzung"+id).style.backgroundColor = "red";
	}else{
		document.getElementById("zusammensetzung"+id).style.backgroundColor = "green";
	}
	document.getElementById("errormsg").innerHTML = reason;

}

function checkIiooWord(){
	var word = document.getElementById("iiooqisch").value;
	if(word.length == 0){
		return;
	}
	var good = true;
	var reason = "";
	// check for syllable markings
	word_syl = word;
	word = word.split("-").join("");
	if (word.length > 5 && word_syl == word) {
		good = false;
		reason = "Das Wort sollte Silbenmarkierungen enthalten. Silben werden durch ein Minuszeichen getrennt.";
	}

	// check if word is in worterbuch
	for(var i = 0; i < words.length; i++){
		if(words[i][0].syllables.split("-").join("") == word){
			good = false;
			reason = "Das Wort ist bereits im Wörterbuch enthalten. Bedeutung: "+words[i][0].translation;
			break;
		}
	}

	// check if the word contains a w that is not directly preceded by a v
	for(var i = 0; i < word.length; i++){
		if(word[i] == "w"){
			if(i == 0 || word[i-1] != "v"){
				good = false;
				reason = "Ein w darf nur direkt vor einem v stehen.";
				break;
			}
		}
	}

	// check if every h is either at index 0 or directly preceded by a c
	for(var i = 0; i < word.length; i++){
		if(word[i] == "h"){
			if(i != 0 && word[i-1] != "c"){
				good = false;
				reason = "Ein h darf nur direkt nach einem c oder am Wortanfang stehen.";
				break;
			}
		}
	}

	// every c must be followed immediately by a h
	for(var i = 0; i < word.length; i++){
		if(word[i] == "c"){
			if(i == word.length-1 || word[i+1] != "h"){
				good = false;
				reason = "Ein c muss direkt von einem h gefolgt werden.";
				break;
			}
		}
	}

	// there may never be the sequence chrr
	if(word.indexOf("chrr") != -1){
		good = false;
		reason = "Der Buchstabe chrr ist im modernen iiooqischen nicht erlaubt.";
	}

	// word may only contain a-z, space, é and ä
	for(var i = 0; i < word.length; i++){
		if(word[i] != " " && word[i] != "é" && word[i] != "ä" && (word[i].charCodeAt(0) < 97 || word[i].charCodeAt(0) > 122)){
			good = false;
			reason = "Das Wort darf nur aus a-z, ä, é und Leerzeichen bestehen.";
			break;
		}
	}

	if(!good){
		document.getElementById("iiooqisch").style.backgroundColor = "red";
	}else{
		
		// word should be more than 3 characters long
		if(word.length <= 3){
			good = false;
			reason = "Das Wort sollte mindestens 4 Zeichen lang sein.";
		}

		// word should be less than 16 characters long
		if(word.length > 15){
			good = false;
			reason = "Das Wort sollte maximal 15 Zeichen lang sein.";
		}

		// word should not contain qu
		if(word.indexOf("qu") != -1){
			good = false;
			reason = "Das Wort sollte kein qu enthalten. Nur in sehr wenigen Fällen ist dies berechtigt.";
		}

		// word should not contain sch
		if(word.indexOf("sch") > -1) {
			good = false;
			reason = "Die Buchstabenfolge sch ist im iiooqischen nur sehr schwierig auszusprechen. Nur in sehr wenigen Fällen ist dies berechtigt.";
		}

		if(word == word_syl) {
			good = false;
			reason = "Das Wort sollte Silbenmarkierungen enthalten, es sei denn, es besteht nur aus einer Silbe.";
		}

		if(good)
			document.getElementById("iiooqisch").style.backgroundColor = "green";
		else
			document.getElementById("iiooqisch").style.backgroundColor = "yellow";
	}
	document.getElementById("errormsg").innerHTML = reason;
}

function checkDeWord(id) {
	var word = document.getElementById("uebersetzung"+id).value;
	if(word.length == 0){
		return;
	}
	var good = true;
	var reason = "";
	// check if word is in worterbuch
	for(var i = 0; i < words.length; i++){
		if(words[i][0].translation == word){
			good = false;
			reason = "Das Wort ist bereits im Wörterbuch enthalten. Bedeutung: "+words[i][0].translation;
			break;
		}
	}

	if(!good){
		document.getElementById("uebersetzung"+id).style.backgroundColor = "red";
	}else{
		document.getElementById("uebersetzung"+id).style.backgroundColor = "green";
	}
}

function submit() {
	//build a json containing the username, iiooqas word, current date and time, and the words with wortart
	function pad2(number) {
		return (number < 10 ? '0' : '') + number;
	}
	var word = document.getElementById("iiooqisch").value;
	var result = {};
	var date = new Date();
	var dateString = date.getFullYear() + "-" + pad2(date.getMonth()+1) + "-" + pad2(date.getDate()) + " " + pad2(date.getHours()) + ":" + pad2(date.getMinutes()) + ":" + pad2(date.getSeconds());
	result["word"] = word;
	result["date"] = dateString;
	result["author"] = "<?php echo $user; ?>";
	result["translations"] = [];
	var table = document.getElementById("uebersetzungen");
	for(var i = 1; i < table.rows.length; i++){
		var row = table.rows[i];
		var word = row.cells[0].children[0].value;
		var wortart = row.cells[1].children[0].value;
		var sprache = row.cells[2].children[0].selectedIndex + 1;
		var dictionarytype = row.cells[3].children[0].value;
		var comment = row.cells[4].children[0].value;
		if(word != "")
			result["translations"].push({"translation":word, "wordtype":wortart, "language":sprache, "dictionarytype":dictionarytype, "comment":comment});
	}
	if(result["translations"].length == 0){
		alert("Bitte mindestens eine Übersetzung hinzufügen.");
		return;
	}
	result["nsfw"] = document.getElementById("nsfw").checked;
	result["zusammensetzungen"] = [];
	var table = document.getElementById("zusammensetzungen");
	for(var i = 1; i < table.rows.length; i++){
		var row = table.rows[i];
		var word = row.cells[0].children[0].value;
		var type = row.cells[1].children[0].value;
		var comment = row.cells[2].children[0].value;
		//word is a iioo word. find the ID for that word, which is in words[i][0].id for some index i that has words[i][0].syllables.split("-").join("") == word
		var newword = words.filter(x=>x[0].syllables.split("-").join("") == word)[0] || -1;
		if(word != "")
			result["zusammensetzungen"].push({"iioo": newword, "type":type, "comment":comment});
	}
	result["comment"] = document.getElementById("globalerkommentar").value;
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
	xhttp.open("POST", "iiooqas/api/dict_addentry.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("string=" + string);
	//disable the submit button
	document.getElementById("submit").disabled = true;
}

function buildintdictionary(){
	var table = document.getElementById("intdictionary");
	var html = "<h2>Dictionary</h2><table>";
	html += words.map(x=>`<tr><td>${x[0].syllables}</td><td>${x.map(y=>y.translation).join(", ")}</td><!--<td><button onclick="loadWord('${x[0].word}')">Load</button></td>--></tr>`).join("");
	table.innerHTML = html;
}
buildintdictionary();

function loadWord(wordtoload){
	//grab wordtoload in words and fill the fields
	var word = words.filter(x=>x[0].word == wordtoload)[0];
	document.getElementById("iiooqisch").value = word[0].syllables;
	//clear previous translations
	var table = document.getElementById("uebersetzungen");
	while(table.rows.length > 1){
		table.deleteRow(1);
	}
	for(var i = 0; i < word.length; i++){
		addTranslation(word[i].translation, word[i].wordtype, word[i].language, word[i].dictionarytype, word[i].comment);
	}
}
</script>