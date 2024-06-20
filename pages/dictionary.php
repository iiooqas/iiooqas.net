<script>
var lang = "<?php echo $lang; ?>";
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
	"aqal": "ADJ",
	"aqan": "ADJ",
	"des": "DES",
	"z": "NR",
	"e": "UNIT",
	"p": "PHRASE",
	"abk": "ABK",
	"": ""
}
var highlightedWords = [];
</script>

<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h1 class="title"><?php t("Dictionary"); ?></h1>
			</div>
			<div class="col-md-4">
				<?php if(isset($_SESSION['user'])){ ?>
					<a href="?page=add_word" class="btn btn-primary"><?php t("Add word"); ?></a>
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
<script>
var direction = 0; //order of iioo and lang columns
function swap() {
	direction = 1 - direction;
	displayWorterbuch();
}
function displayWorterbuch() {
	var table = direction == 0 ? `
		<tr>
			<th><?php echo t("iiooqas"); ?> <button onclick='swap();' class='btn btn-sm'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5"/>
</svg></button></th>
			<th><?php echo ($lang == "de" || $lang == "iioode") ? "deutsch" : "english"; ?></th>
		</tr>` : `
		<tr>
			<th><?php echo ($lang == "de" || $lang == "iioode") ? "deutsch" : "english"; ?> <button onclick='swap();' class='btn btn-sm'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5"/>
</svg></button></th>
			<th><?php echo t("iiooqas"); ?></th>
		</tr>`;
	var keys = Object.entries(direction == 0 ? dbtranslations : dbreverse).sort((a,b)=>a[0].localeCompare(b[0]));
	var suchwort = document.getElementById("suche").value.toLowerCase();
	var needsHighlight = highlightedWords.length > 0;
	if (suchwort != ""){
		keys = keys.filter(a=>(a[1][0].word.includes(suchwort)) || (a[1].some(a=>a.translation.toLowerCase().includes(suchwort))));
	}
	keys = keys.filter(a=>a[1][0].isNSFW == 0);//improvable filter
	keys.sort((a,b)=>a[0].localeCompare(b[0]));
	for (var i = 0; i < keys.length; i++) {
		var word = keys[i][1][0];
		var translation = keys[i][1];
		var transString = "";
		for (var j = 0;j < wortartOrder.length; ++j){ //print the translations in order of wortartOrder
			if(translation.some(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j])){ //if there is a translation with this wordtype
				if(transString != "") transString += ", ";
				transString += translation.filter(a=>wortartOrderDisplay[a.wordtype]==wortartOrder[j]).map(a=>a.translation).join(", ");
			}
		}
		if(transString != "")
			table += direction == 0 ? `<tr>
				<td ${needsHighlight&&highlightedWords.indexOf(word.word)!=-1?"style='color:yellow;'":""}>
				<a href="?page=word&word=${word.word}">${word.word}</a>
				</td>
				<td>${transString}</td>
			</tr>` : `<tr>
				<td>${transString}</td>
				<td ${needsHighlight&&highlightedWords.indexOf(word.word)!=-1?"style='color:yellow;'":""}>
				<a href="?page=word&word=${word.word}">${word.word}</a>
				</td>`;
	}
	table += ``;
	document.getElementById("worterbuch").innerHTML = table;
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
	document.getElementById(("suche")).attributes.placeholder.value = "<?php t("Search in % words"); ?>".replace("%",Object.keys(dbtranslations).length);
}

$.ajax({url:"iiooqas/api/dict_getoverview.php?lang=<?php echo $langid; ?>", success:(x) => initWorterbuch(x)});
</script>