<div class="container">
	<div class="row">
		<div class="col-12">
		<h1>Projects</h1>
		<div class="row">
<?php
function card($im, $title, $link, $text){
	echo <<<HTML
	<div class="card mb-4" style="max-width: 540px;">
	<div class="row g-0">
		<div class="col-md-4">
			<img src="iiooqas/assets/$im" class="img-fluid rounded-start" alt="image of $title"/>
		</div>
		<div class="col-md-8">
			<div class="card-body">
			<h4 class="card-title"><a href="?page=project_$link">$title</a></h4>
			<p class="card-text">$text</p>
			<!--<p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>-->
		</div>
	</div>
	</div>
</div>
HTML;
}

card("minecraft.jpg", "Minecraft", "mc", "Complete translation of minecraft 1.19.2 into iiooqas.");
card("wikipedia.png", "Wikipedia", "wiki", "A <a href='http://hutfless.net/iiooqas/wiki'>mediawiki instance</a>, translated into iiooqas, for articles in iiooqas. Integrated into the real wikipedia translation overview with a tampermonkey script.");
card("websites.png", "Websites", "websites", "A tampermonkey script to translate parts of various websites into iiooqas.");
card("music.jpg", "Songs", "songs", "Various songs translated into iiooqas and some written only in iiooqas.");
card("tasarna.png", "tasarna", "tasarna", "A game described in iiooqas.");
card("flagge.png", "This website", "site", "A place for the language itself and its documentation.");
card("files.jpeg", "Files", "files", "A collection of files related to iiooqas.");
?>
</div>
<small>
Minecraft Logo and block texture are copyrighted by Mojang Studios<br/>
Wikipedia logo taken unchanged from en.wikipedia.org, where it is licensed under <a href="https://creativecommons.org/licenses/by-sa/3.0/deed.en">CC BY-SA 3.0</a><br/>
</small>
		</div>
	</div>
</div>