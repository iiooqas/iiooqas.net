<?php
session_start();

include_once "db/connect.php";
include_once "db/words.php";
include_once "db/authors.php";

$page = "index";
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
	$userdata = getAuthor($user);
	$page = "home";
}
if(isset($_GET['page'])){
	$page = $_GET['page'];
}
$lang = "de";
if(isset($_SESSION['lang'])){
	$lang = $_SESSION['lang'];
}
if(isset($_GET['lang'])){
	$lang = $_GET['lang'];
	$_SESSION['lang'] = $lang;
}
$langid = 1;
if($lang == "en") $langid = 2;
if($lang == "iioode") $langid = 1;
if($lang == "iiooen") $langid = 2;

function translatep($en, $lang, $param) {
	$tr = translate($en, $lang);
	//replace the placeholder % by the parameter
	return str_replace("%", $param, $tr);
}
function translate($en, $lang) {
	if($lang == "en") return $en;
	$translations = array(
		"de" => array(
			"Dictionary" => "Wörterbuch",
			"Home" => "Startseite",
			"Projects" => "Projekte",
			"Log in" => "Anmelden",
			"Log out" => "Abmelden",
			"Community" => "Gemeinschaft",
			"Learn" => "Lernen",
			"Other" => "Sonstiges",
			"Light" => "Hell",
			"Dark" => "Dunkel",
			"Language" => "Sprache",
			"Automatic" => "Automatisch",
			"Imprint" => "Impressum",
			"Data protection" => "Datenschutz",
			"Search in % words" => "Suche in % Wörtern",
			"iiooqas" => "iiooqas",
			"added on" => "hinzugefügt am",
			"Translations" => "Übersetzungen"
		),
		"iiooen" => array(
			"Dictionary" => "nufterogli",
			"Home" => "mnitisza",
			"Learn" => "qiztol",
			"Other" => "ikqal",
			"Light" => "irtrach",
			"Dark" => "hellt",
			"Language" => "larxas",
			"Automatic" => "lavtras",
			"Search in % words" => "gachtaan za % hitiré",
			"iiooqas" => "iioofikial",
			"added on" => "alitrapat gittra",
			"Translations" => "takavané"
		)
	);
	$translations["iioode"] = $translations["iiooen"];
	if(isset($translations[$lang][$en])){
		return $translations[$lang][$en];
	}
	return $en;
}
function t($en) {
	global $lang;
	echo translate($en, $lang);
}
function tp($en, $param) {
	global $lang;
	echo translatep($en, $lang, $param);
}
?><!DOCTYPE html>
<html data-bs-theme="dark">
<head>
	<title>iiooqas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="description" content="Website of the constructed language iiooqas."/>

	<link rel="stylesheet" type="text/css" href="style.css"/>
	<!-- bootstrap css cdn -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet"/>
	<link href="https://getbootstrap.com/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
	<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet"/>
	<!-- font awesome cdn -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
	<!-- jquery cdn -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- bootstrap js cdn -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<!-- font awesome cdn -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
	<script src="iiooqas/color.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body>
<?php
include_once "header.php";
include_once "pages/$page.php";
?>
<div style="position:fixed;left:0;bottom:0;"><a href="?page=contact">Datenschutz, Impressum</a></div>
</body>
</html><?php 
$db->close();
?>