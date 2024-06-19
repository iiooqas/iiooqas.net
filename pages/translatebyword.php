<script>
var lang = "<?php echo $lang; ?>";

function tokenize(text){
    var out = [];
    var cur = [];
    //all a-zéä chars are a word token, the rest is seperator token
    for(var i = 0;i < text.length;++i){
        if("abcdefghijklmnopqrstuvwxyzéä".includes(text[i])){
            cur.push(text[i]);
        } else {
            if(cur.length > 0){
                out.push(cur.join(""));
                cur = [];
            }
            out.push(text[i]);
        }
    }
    if(cur.length > 0){
        out.push(cur.join(""));
    }
    return out;
}

function tr(){
    var input = document.getElementById("input").value;
    var input2 = document.getElementById("input2").value.split("\n");
    var words = input.split("\n").map(a=>tokenize(a));
    var outputMap = a => {
        if(map[a]){
            return "<span style='color:green;' title='"+map[a]+"'>"+a+"</span>";
        } else {
            return a == "\n" ? "<br>" : a;
        }
    };
    var out = "<table>";
    for(var i = 0;i < words.length;++i){
        out += "<tr>";
        out += "<td>" + (input2[i] || "") + "</td>";
        out += "<td>";
        for(var j = 0;j < words[i].length;++j){
            out += outputMap(words[i][j])
        }
        out += "</td>";
        out += "</tr>";
    }
    out += "</table>";
    document.getElementById("output").innerHTML = out;
}
</script>

<section class="section">
	<div class="container">
		<h1 class="title">
			translate by word</h1>
        <textarea id="input" class="textarea" placeholder="iioo"></textarea>
        <textarea id="input2" class="textarea" placeholder="de"></textarea>
        <button class="button is-primary" onclick="tr()">Translate</button>
        <div class="row" id="output">
        </div>
    </div>
</section>
<script>
var db;
var dbtranslations = {};
function initWorterbuch(json){
	db = json.words;
	for(var i = 0;i < db.length;++i){
		if(dbtranslations[db[i].word] == undefined){
			dbtranslations[db[i].word] = [];
		}
		dbtranslations[db[i].word].push(db[i]);
	}
	displayWorterbuch();
}

$.ajax({url:"api/dict_getoverview.php", success:(x) => initWorterbuch(x)});

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

var map = {
    "jesus": "name",
    "galiläa": "place",
    "andreeas": "name",
    "jesaja": "name",
    "nazarät": "place",
    "jordan": "place",
    "simon": "name",
    "joannes": "name",
    "satan": "name",
    "kafarnaum": "place",
    "jakobuss": "name",
    "ni": "nil vor vokalen und vor l",
    "gis": "Akk von gi",
    "git": "Gen von gi",
    "djat": "Gen von djam",
    "djas": "Akk von djam",
    "jerusalemm": "place",
    "zebedäus": "name",
    "saabat": "veranstaltung"
};
function displayWorterbuch(){
	var keys = Object.entries(dbtranslations);
	for (var i = 0; i < keys.length; i++) {
		var word = keys[i][1][0];
		var translation = keys[i][1];
		var transString = translation.map(a=>a.translation).join(", ");
        map[word.word] = "NN "+transString;
        map[appendEnding(word.word, "i")] = "Verb Present 1.P. sg "+transString;
        map[appendEnding(word.word, "arv")] = "Verb Present 2.P. sg "+transString;
        map[appendEnding(word.word, "a")] = "Verb Present 3.P. sg "+transString;
        map[appendEnding(word.word, "ä")] = "Verb Present 3.P. pl "+transString;
        map[appendEnding(word.word, "li")] = "Verb Past 1.P. sg "+transString;
        map[appendEnding(word.word, "erv")] = "Verb Past 2.P. sg "+transString;
        map[appendEnding(word.word, "rit")] = "Verb Past 3.P. sg "+transString;
        map[appendEnding(word.word, "äs")] = "Verb Past 3.P. pl "+transString;
        map[appendEnding(word.word, "lip")] = "Verb Past passive 1.P. sg "+transString;
        map[appendEnding(word.word, "erp")] = "Verb Past passive 2.P. sg "+transString;
        map[appendEnding(word.word, "rip")] = "Verb Past passive 3.P. sg "+transString;
        map[appendEnding(word.word, "äp")] = "Verb Past passive 3.P. pl "+transString;
        map[appendEnding(word.word, "im")] = "Verb befehl "+transString;
        map[appendEnding(word.word, "é")] = "Noun Plural "+transString;
        map[appendEnding(word.word, "s")] = "Noun Accusative "+transString;
        map[appendEnding(word.word, "és")] = "Noun Accusative Plural "+transString;
        map["des"+word.word] = "des-form adjective "+transString;
	}
    tr();
}
</script>