<div class="container">
	<div class="row">
		<div class="col-12">
		    <h1>Tutorial</h1>
            <?php
            $tpage = "index";
            if(isset($_GET['tpage'])){
                $tpage = $_GET['tpage'];
            }
            $tlang = $lang;
            if($tlang == "iiooen") $tlang = "iioo";
            else if($tlang == "iioode") $tlang = "iioo";
            @include_once("tutorial_$tpage.$lang.php");
            ?>
        </div>
    </div>
</div>
<script>
function load() {
    [...document.querySelectorAll(".iiooqas-exercise")].map(e => {
        var cont = e.innerHTML.split("\n");
        var curLetterIndex = 0;
        var execMemory = "";
        cont = cont.map(c => {
            c = c.split("\t").join("");
            var first = c.slice(0, 4);
            var last = c.slice(5);
            if (first == "TASK") return "<b>" + last + "</b>";
            if (first == "EXEC"){ execMemory = "<span class=''>" + "abcdefghijklmnopqrstuvwxyz"[curLetterIndex++]+") "+last + "</span>"; return "";}
            if (first == "SOLU") return execMemory + "<button class='button' style='height:20px;float:right;font-size:10px;' onclick='alert(\""+last+"\");'>Solution</button>";
            return "";
        });
        var out = cont.filter(a => a != "").join("<br/>");
        e.innerHTML = '<div class="box" style="border-left-color:red;">' + out + "</div>";
    });

    [...document.querySelectorAll(".iiooqas-example")].map(e => {
        var cont = e.innerHTML.split("\n");
        cont = cont.map(c => {
            c = c.split("\t").join("");
            c = c.split("|");
            if (c.length < 2) return "";
            return c[0] + " → " + c[1];
        });
        var out = cont.filter(a => a != "").join("<br/>");
        e.innerHTML = '<div class="box">' + out + "</div>";
    });

    [...document.querySelectorAll(".iiooqas-vocab")].map(e => {
        var cont = e.innerHTML.split("\n");
        cont = cont.map(c => {
            c = c.split("\t").join("");
            c = c.split("|");
            if (c.length < 2) return "";
            return "<td>" + c[0] + "</td><td>" + c[1] + "</td>";
        });
        var out = "<table class='table table-condensed'><tr>" + cont.filter(a => a != "").join("</tr><tr>") + "</tr></table>";
        e.innerHTML = '<div class="box" style="border-left-color:green;"><b>Vokabeln für Lektion ' + e.attributes["data-lektion"].value + '</b><hr/>' + out + "</div>";
    });
}
load();
</script>
<style>
.iiooqas-example > .box {
    border-left: 3px solid #1bbd32; /* according to colornames.org, this color is named My Turtle That Ran Away */
    padding-left: 10px;
    margin-top: 5px;
    margin-bottom: 4px;
}
</style>