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
</script>

<audio id="audio_1">
	<source src="audio/aaltéér.mp3" type="audio/mp3" preload="none">
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

function details2(json) {
	var html = "";
	var word = json.words[0];
	html += word.word + " (" + word.syllables + (word.pronounciation != "" ? ", pronounced as " + word.pronounciation : "") + ")<br/>";
	html += "added on "+word.date+" by "+json.author.filter(a=>a.id == word.author)[0].displayname+"<br/>";
	html += word.comment ? "Comment: "+word.comment+"<br/>" : "";
	html += "<br/>Translations:<br/>";
	for(var i = 0; i < json.translations.length; i++) {
		var translation = json.translations[i];
		html += "<b>"+translation.language+":</b> "+translation.translation+"<br/>";
	}
	document.getElementById("content").innerHTML = html;
}

details("<?php echo $_GET['word']; ?>");
</script>