<script>
var lang = "<?php echo $lang; ?>";
var wortarten = {
	"v":"Verb",
	"n":"Nomen",
	"a":"Adjektiv",
	"des":"Vollendetes Adjektiv [des]",
	"np":"Nomenphrase",
	"ap":"Adjektivphrase",
	"vp":"Verbphrase",
	"aa":"Altiiooqisches Adjektiv",
	"av":"Altiiooqisches Verb",
	"an":"Altiiooqisches Nomen",
	"p":"Phrase",
	"z":"Zahlwort",
	"e":"Einheit",
	"":"Sonstiges/unbekannt",
	"abk":"Abkürzung"
};
//wort translation by wordtype in the order n,np,an,v,vp,av,a,ap,aa,des,z,e,p,abk
var wortartOrder = ["NN","VER","ADJ","DES","NR","UNIT","PHRASE","ABK", ""];
var wortartOrderDisplay = {
	"n": "NN",
	"np": "NN",
	"an": "NN",
	"v": "VER",
	"vp": "VER",
	"av": "VER",
	"a": "ADJ",
	"ap": "ADJ",
	"aa": "ADJ",
	"des": "DES",
	"aqal": "ADJ",
	"aqan": "ADJ",
	"z": "NR",
	"e": "UNIT",
	"p": "PHRASE",
	"abk": "ABK",
	"": ""
}
var highlightedWords = [];
<?php if(!isset($_GET['word'])) die("no word provided!</script></div></div></body></html>"); ?>
</script>

<audio id="audio_1">
	<source src="audio/<?php echo $_GET['word']; ?>.mp3" type="audio/mp3" preload="none">
</audio>

<section class="section">
	<div class="container">
		<h1 class="title"><?php echo $_GET['word']; ?></h1>
		<div id="content"></div>
	</div>
</section>
<script>
function playAudio(word) {
	if(wordsWithAudio.indexOf(word) == -1){
		alert("Audio nicht verfügbar");
		return;
	}
	//load source
	var audio = document.getElementById("audio_1");
	var source = audio.children[0];
	source.src = "audio/"+word+".mp3";
	audio.load();
	audio.play();
}

function details(word) {
	$.ajax({url:"iiooqas/api/dict_getdetails.php?word=" + word, success:(x) => details2(x)});
}

function grammar_plural(word){
	if(word == "artainne") return "artainneé";
	if(word[word.length-1] == "é")
		return word.substr(0, word.length-1) + "eé";
	return word+"é";
}

function appendEnding(word, ending) {
	//if the word ends in a single wovel, replace it with the ending
	var wovels = "aeiouäé";
	if(word.length < 2) return word+ending;
	if(wovels.includes(word[word.length-1]) && word[word.length-2] != word[word.length-1]){
		return word.substring(0, word.length-1)+ending;
	} else {
		return word+ending;
	}
}

function prepend(a, b){
	if(a == "kra" && b[0] == "a" && b[1] != "a") return prepend("kr", b);
	return a+b;
}

function removeLastLetter(word) {
	//if the word ends in chr, ch, aa, ee, éé, ii, oo, uu, ss or pf, remove that part of the word instead of the last letter
	if(word.length > 2 && word.slice(-3) == "chr")
		return word.substring(0, word.length-3);
	if(word.length > 1 && ["ch", "aa", "ee", "éé", "ii", "oo", "uu", "ss", "pf"].includes(word.slice(-2)))
		return word.substring(0, word.length-2);
	return word.substring(0, word.length-1);
}

function appendEndingToFirstWord(word, ending) {
	var spl = word.split(" ");
	spl[0] = appendEnding(spl[0], ending);
	return spl.join(" ");
}

function genNounGrammar(word){
	return `Noun forms:<br/>
	<table class='table'><tr><td><b>${word}</b> singular</td><td><b>${grammar_plural(word)}</b> plural</td>
	<td><b>${grammar_plural(word)+"nes"}</b> numbered plural</td></tr></table>`;
}

function genVerbGrammar(word){
	var table = `i aa li u
arv rulnmf erv urv
a at rit u
eedt ardt addt 
tel tal lep tul
tep tap terp tup
tas taas tes tus
sal frig séél sul
ä us äs äu
ip ap lip up
arvp rulp erp urp
ap arp rip up
geep arp aap 
telp talp leep tulp
teep tap teerp tuup
tasp taap tep tup
salp frip séép sulp
äp up äp äp`.split("\n").map(a=>a.split(" "));
	var html = `Verb forms:<br/>
	<table class='table'>
	<th><td>present</td><td>past</td><td>future</td><td>undefined time</td><td>probable future</td></th>`;
	for(var i=0;i<table.length;++i) {
		html += "<tr>";
		html += "<td>"+`1.P. singular active;2.P. singular active;3.P. singular active;3.P undefined singular active;inclusive 1.P. plural active;exclusive 1.P. plural active;all-encompassing 1.P. plural active;2.P. plural active;3.P. plural active;1.P. singular passive;2.P. singular passive;3.P. singular passive;3.P undefined singular passive;inclusive 1.P. plural passive;exclusive 1.P. plural passive;all-encompassing 1.P. plural passive;2.P. plural passive;3.P. plural passive`.split(";")[i]+"</td>";
		for(var j=0;j<table[i].length;++j){
			html += "<td>";
			if(table[i][j] != "") html += appendEnding(word, table[i][j]);
			html += "</td>";
		}
		html += "<td>"+prepend("kra",appendEnding(word, table[i][0]))+"</td>";
		html += "</tr>";
	}
	return html + "</table>";
}

function genAdjectiveGrammar(word){
	return `Adjective forms:<br/>
	<table class='table'>
		<tr>
			<td>${appendEnding(word, "ut")} - quantitative 1</td>
			<td>${appendEnding(word, "u")} - quantitative 2</td>
			<td>${appendEnding(word, "ot")} - quantitative 3</td>
			<td>${appendEnding(word, "it")} - quantitative -1</td>
		</tr>
		<tr>
			<td>${appendEnding(word, "ast")} - qualitative 1</td>
			<td>${appendEnding(word, "ist")} - qualitative -1</td>
		</tr>
	</table>
	`;
}
var languagesnames = ["error", "DE", "EN", "EO"];
function details2(json) {
	var html = "";
	var word = json.words[0];
	html += word.word + " (" + word.syllables + (word.pronounciation != "" ? ", pronounced as " + word.pronounciation : "") + ")<br/>";
	html += "<?php echo t("added on");?> "+word.date+" by "+json.author.filter(a=>a.id == word.author)[0].displayname+"<br/>";
	html += word.comment ? "Comment: "+word.comment+"<br/>" : "";
	html += "<br/>Translations:<table class='table'>";
	for(var i = 0; i < json.translations.length; i++) {
		var translation = json.translations[i];
		html += "<tr><td><b>"+languagesnames[translation.language]+":</b> "+translation.translation+"</td><td>"+[...json.author.filter(a=>a.id == translation.author),{"displayname":""}][0].displayname+"</td><td>"+translation.date+"</td></tr>";
	}
	html += "</table>";
	html += "<br/><br/><hr/>";
	html += genNounGrammar(word.word);
	html += genVerbGrammar(word.word);
	html += genAdjectiveGrammar(word.word);
	document.getElementById("content").innerHTML = html;
}

details("<?php echo $_GET['word']; ?>");
</script>