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
		<div class="row">
			<div class="col-md-8">
				<h1 class="title">nufterogli</h1>
			</div>
			<div class="col-md-4">
				<?php if(isset($_SESSION['user'])){ ?>
					<a href="?page=add_word" class="btn btn-primary">Wort hinzufügen</a>
				<?php } ?>
			</div>
		</div>
		<div class="field">
			<div class="control">
				<input style="font-size:25px;height:47px;width:100%;" class="input input-xl" type="text" placeholder="Text" id="suche" onchange="displayWorterbuch();" onkeyup="displayWorterbuch();"/>
			</div>			
		</div>
	<table class="table is-hoverable is-fullwidth" id="worterbuch">
	</table>
	<div id="stats"></div>
</div>
</section>
<div class="modal fade" id="dictDetailModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Details</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modalcontent"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
var wordsWithAudio = [<?php
//Audio files are in audio/[iioo].mp3
$audio = glob("audio/*.mp3");
$audio = array_map(function($a){return substr($a, 6, -4);}, $audio);
$audio = array_filter($audio, function($a){return preg_match("/^[a-zéä]+$/", $a);});
$audio = array_map(function($a){return "'$a'";}, $audio);
echo implode(",", $audio);
?>];

function searchForLetter(letter,iioo = true) {
	document.getElementById("suche").value = letter;
	//only search at the start of iioo words
	document.getElementById("anfang").checked = true;
	//only search in iioo words
	document.getElementById("iioo").checked = iioo;
	document.getElementById("de").checked = !iioo;
	displayWorterbuch();
}

//from worterbuch.js, generate a table with all words
var direction = 0;
function swap() {
	direction = 1 - direction;
	displayWorterbuch();
}
function displayWorterbuch() {
	var table = direction == 0 ? `
		<tr>
			<th>iioofikial <button onclick='swap();' class='btn btn-sm'>&lt;=&gt;</button></th>
			<th>deutsch</th>
		</tr>` : `
		<tr>
			<th>deutsch <button onclick='swap();' class='btn btn-sm'>&lt;=&gt;</button></th>
			<th>iioofikial</th>
		</tr>`;
	var keys = Object.entries(direction == 0 ? dbtranslations : dbreverse).sort((a,b)=>a[0].localeCompare(b[0]));
	var suchwort = document.getElementById("suche").value.toLowerCase();
	var searchIioo = true;//document.getElementById("iioo").checked;
	var searchDe = true;// document.getElementById("de").checked;
	var show = 'word';//document.getElementById("show").value;
	var needsHighlight = highlightedWords.length > 0;
	if (suchwort != ""){
		//if (document.getElementById("anfang").checked){
		//	keys = keys.filter(a=>(searchIioo && a[1][0].word.startsWith(suchwort)) || (searchDe && a[1].some(a=>a.translation.startsWith(suchwort))));
		//} else {
			keys = keys.filter(a=>(searchIioo && a[1][0].word.includes(suchwort)) || (searchDe && a[1].some(a=>a.translation.toLowerCase().includes(suchwort))));
		//}
	}
	//if (!document.getElementById("nsfw").checked){
		keys = keys.filter(a=>a[1][0].isNSFW == 0);//improvable filter
	//}
	keys.sort((a,b)=>a[0].localeCompare(b[0]));
	for (var i = 0; i < keys.length; i++) {
		var word = keys[i][1][0];
		var translation = keys[i][1];
		var transString = "";
		for (var j = 0;j < wortartOrder.length; ++j){
			//if there is a translation with this wordtype
			if(translation.some(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j])){
				if(transString != "") transString += ", ";
				transString += translation.filter(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j]).map(a=>a.translation).join(", ");
			}
		}
		table += direction == 0 ? `<tr>
				<td ${needsHighlight&&highlightedWords.indexOf(word.word)!=-1?"style='color:yellow;'":""}>
				<a href="?page=word&word=${word.word}">${show=='syllables'?word.syllables:word.word}</a>
				</td>
				<td>${transString}</td>
			</tr>` : `<tr>
				<td>${transString}</td>
				<td ${needsHighlight&&highlightedWords.indexOf(word.word)!=-1?"style='color:yellow;'":""}>
				<a href="?page=word&word=${word.word}">${show=='syllables'?word.syllables:word.word}</a>
				</td>`;
	}
	table += ``;
	document.getElementById("worterbuch").innerHTML = table;
}

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

var db;
var dbtranslations = {};
var dbreverse = {};
function initWorterbuch(json) {
	db = json.words;
	for(var i = 0;i < db.length; ++i){
		if(dbtranslations[db[i].word] == undefined){
			dbtranslations[db[i].word] = [];
		}
		dbtranslations[db[i].word].push(db[i]);

		if(dbreverse[db[i].translation] == undefined){
			dbreverse[db[i].translation] = [];
		}
		dbreverse[db[i].translation].push(db[i]);
	}
	document.getElementById(("suche")).attributes.placeholder.value = "Suche in "+Object.keys(dbtranslations).length+" Wörtern";
}

$.ajax({url:"iiooqas/api/dict_getoverview.php", success:(x) => initWorterbuch(x)});
</script>