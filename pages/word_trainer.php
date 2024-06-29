<script>
	function format(number) {
	var upper = (number-(number%1000))/1000;
	var lower = number%1000;
	function pad2(x){return x < 10 ? "0"+x:x;}
	function pad3(x){return x < 100 ? "0"+pad2(x):x;}
	if(lower == 0) return upper;
	if(lower % 100 == 0) return upper + "." + Math.floor(lower/100);
		return upper + "." + pad3(lower);
	}

	var words = [["nak", "Ente"],["sejyté", "Ziege"]];
	var points = 42000;
	var timeout = undefined;

	function getRandomWord(){
		var feedback = document.getElementById("feedback");
		randomIndex = Math.floor(Math.random() * words.length);
		var randomWord = words[randomIndex][0];
		var randomTranslation = words[randomIndex][1];
		document.getElementById("word").innerHTML = randomWord;
		document.getElementById("translation").value = "";
	}

	function checkTranslation(event){
		if (event.keyCode != 13) {
			return;
		}
		var translation = document.getElementById("translation").value;
		var feedback = document.getElementById("feedback");
		if(translation == words[randomIndex][1]){
			givePoints(1000);
			feedback.innerHTML = "Correct!";
			feedback.style.backgroundColor = "lightgreen";
		} else {
			givePoints(translation.length == 0 ? -100 : 100);
			feedback.innerHTML = "Incorrect! The correct translation is: " + words[randomIndex][1];
			feedback.style.backgroundColor = "red";
		}
		getRandomWord();
		if(timeout !== undefined){
			clearTimeout(timeout);
		}
		timeout = setTimeout(function(){
			feedback.innerHTML = "";
			feedback.style.backgroundColor = "transparent";
		}, 2000);
	}

	function givePoints(_points){
		points += _points;
		document.getElementById("points").innerHTML = "Points: " + format(points);
	}

	document.addEventListener("DOMContentLoaded", function(){
		getRandomWord();
	});

</script>


<span id="word"></span>
<br/>
<input type="text" id="translation" value="" onkeypress="checkTranslation(event)"/>

<div id="feedback"></div>
<div id="points">Points: 0</div>