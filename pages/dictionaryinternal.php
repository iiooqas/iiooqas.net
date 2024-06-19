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
				<p class="subtitle">
					derat ris <script>document.write(4242);</script> hitiré
				</p>
			</div>
			<div class="col-md-4">
				<?php if(isset($_SESSION['user'])){ ?>
					<a href="?page=add_word" class="btn btn-primary">Wort hinzufügen</a>
				<?php } ?>
			</div>
		</div>
		<div class="field">
			<label class="label">hitir</label>
			<div class="control">
				<input class="input" type="text" placeholder="Text" id="suche" onchange="displayWorterbuch();" onkeyup="displayWorterbuch();"/>
			</div>
			Nur in Wortanfang suchen: <input type="checkbox" id="anfang" onchange="displayWorterbuch();"/>
			Include NSFW words: <input type="checkbox" id="nsfw" onchange="displayWorterbuch();"/><br/>
			Suche in: iioofikial <input type="checkbox" id="iioo" onchange="displayWorterbuch();" checked/>
			deutsch <input type="checkbox" id="de" onchange="displayWorterbuch();" checked/><br/>
			Wort anzeigen: <select id="show" onchange="displayWorterbuch();">
				<option value="normal">normal</option>
				<option value="syllables">syllables</option>
				<option value="stress">stress</option>
				<option value="both">both</option>
			</select>
			<?php
$alphabet = ["q","a","á","aa","b","ch","chr","d","e","ee","éé","f","g","h","i","ii","j","k","l","m","n","o","oo","p","r","s","t","u","uu","v","vw","x","y","z"];
echo "<div class='buttons'>iioo ";
foreach($alphabet as $letter){
	echo "<button class='button' onclick='searchForLetter(\"$letter\");'>$letter</button>";
}
echo "</div>";
$alphabet = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
echo "<div class='buttons'>de ";
foreach($alphabet as $letter){
	echo "<button class='button' onclick='searchForLetter(\"$letter\",false);'>$letter</button>";
}
echo "</div>";
			?>
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
function displayWorterbuch() {
	var table = `
		<tr>
			<th>iioofikial</th>
			<th>deutsch</th>
			<th></th>
		</tr>`;
	var keys = Object.entries(dbtranslations);
	var suchwort = document.getElementById("suche").value.toLowerCase();
	var searchIioo = document.getElementById("iioo").checked;
	var searchDe = document.getElementById("de").checked;
	var show = document.getElementById("show").value;
	var needsHighlight = highlightedWords.length > 0;
	if (suchwort != ""){
		if (document.getElementById("anfang").checked){
			keys = keys.filter(a=>(searchIioo && a[1][0].word.startsWith(suchwort)) || (searchDe && a[1].some(a=>a.translation.toLowerCase().startsWith(suchwort))));
		} else {
			keys = keys.filter(a=>(searchIioo && a[1][0].word.includes(suchwort)) || (searchDe && a[1].some(a=>a.translation.toLowerCase().includes(suchwort))));
		}
	}
	if (!document.getElementById("nsfw").checked){
		keys = keys.filter(a=>a[1][0].isNSFW == 0);//improvable filter
	}
	keys.sort((a,b)=>a[0].localeCompare(b[0]));
	for (var i = 0; i < keys.length; i++) {
		var word = keys[i][1][0];
		var translation = keys[i][1];
		var transString = "";
		for (var j = 0;j < wortartOrder.length; ++j){
			//if there is a translation with this wordtype
			if(translation.some(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j])){
				transString += wortartOrder[j] + ": " + translation.filter(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j]).map(a=>a.translation).join(", ") + "<br/>";
			}
		}
		table += `<tr>
				<td ${needsHighlight&&highlightedWords.indexOf(word.word)!=-1?"style='color:yellow;'":""}>${show=='syllables'?word.syllables:word.word}</td>
				<td>${transString}</td>
				<td style='width:167px;'>
					<button class="btn btn-secondary btn-sm" onclick="playAudio('${word.word}')" ${wordsWithAudio.indexOf(word.word)==-1?" disabled='disabled'":""}>Audio</button>
					<button class="btn btn-secondary btn-sm" onclick="details('${word.word}')" data-bs-toggle="modal" data-bs-target="#dictDetailModal">Details</button>
				</td>
			</tr>`;
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

function details(word) {
	$.ajax({url:"api/dict_getdetails.php?word=" + word, success:(x) => details2(x)});
}
function details2(json) {
	var word = json.words[0];
	//show modal
	var modal = document.querySelector(".modal");
	modal.classList.add("is-active");
	var modalcontent = document.getElementById("modalcontent");
	modalcontent.innerHTML = `
		<div class="box">
			<h1 class="title">${word.word}</h1>
			<!--<p class="subtitle">${0}</p>-->

			<audio id="audio_${word.word}">
				<source src="audio/${word.word}.mp3" type="audio/mp3">
			</audio>

			<div class="columns">
				<div class="column">
			${word.date?"gittra: "+word.date+"<br/>":""}
			Autor: ${json.author[0].iiooname}</div>
				<div class="column">
			<button class="btn btn-secondary" onclick="document.getElementById('audio_${word.word}').play()">Audio</button></div>
			</div>
			<table class="table is-hoverable is-fullwidth">
				<tr>
					<th>deutsch</th>
					<th>grammatikalische Kategorie</th>
				</tr>
				${json.translations.map(a=>`
				<tr>
					<td>${a.translation}</td>
					<td>${wortarten[a.wordtype]||"unbekannt"}</td>
				</tr>
				`).join("")}
			</table>
		</div>
	`;
}

var db;
var dbtranslations = {};
function initWorterbuch(json) {
	db = json.words;
	for(var i = 0;i < db.length; ++i){
		if(dbtranslations[db[i].word] == undefined){
			dbtranslations[db[i].word] = [];
		}
		dbtranslations[db[i].word].push(db[i]);
	}
	displayWorterbuch();
}

$.ajax({url:"iiooqas/api/dict_getoverview.php", success:(x) => initWorterbuch(x)});
</script>