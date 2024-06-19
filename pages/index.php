<div class="container">
	<h1>Willkommen<?php if(isset($_SESSION['user'])) echo " ".$userdata["iiooname"]; ?>!</h1>
	<p>iiooqas ist eine konstruierte apriorische Sprache.</p>

	<a class="btn btn-primary" href="https://discord.gg/r9Esm74Q5p"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-discord" viewBox="0 0 16 16">
		<path d="M13.545 2.907a13.2 13.2 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.2 12.2 0 0 0-3.658 0 8 8 0 0 0-.412-.833.05.05 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.04.04 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032q.003.022.021.037a13.3 13.3 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019q.463-.63.818-1.329a.05.05 0 0 0-.01-.059l-.018-.011a9 9 0 0 1-1.248-.595.05.05 0 0 1-.02-.066l.015-.019q.127-.095.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.05.05 0 0 1 .053.007q.121.1.248.195a.05.05 0 0 1-.004.085 8 8 0 0 1-1.249.594.05.05 0 0 0-.03.03.05.05 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.2 13.2 0 0 0 4.001-2.02.05.05 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.03.03 0 0 0-.02-.019m-8.198 7.307c-.789 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612m5.316 0c-.788 0-1.438-.724-1.438-1.612s.637-1.613 1.438-1.613c.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612"/>
	</svg> Discord</button>
	<a class="btn btn-outline-warning" href="https://instagram.com/iiooqas">instagram <i class="fab fa-instagram"></i></a>
	<a class="btn btn-outline-danger" href="https://www.youtube.com/channel/UCMSXbTRid4H5I0om2xuiOkQ">youtube <i class="fab fa-youtube"></i></a>
	<a class="btn btn-outline-success" href="https://github.com/iiooqas"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
		<path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/>
	</svg> Github</a>

	<!-- <h3>Wie kann man das Lernen</h3>
	<ul>
		<li>Inkrementelles Tutorial
		<li>Reference Grammar + Dictionary
		<li>Interaktion mit der Community auf <a href="https://discord.gg/r9Esm74Q5p">Discord</a>
		<li>Interactive Duolingo-style trainer
	</ul>

	<h3>Was kann man damit machen</h3>
	siehe Projekte

	<h3>Neuigkeiten</h3>
	2023-12-31 - Release <a href="?page=projects_mc">Minecraft Übersetzung v5</a><br/>
	2023-12-20 - 2100 Wörter im <a href="?page=dictionary">Wörterbuch</a><br/>
	2022-07-22 - 1000 Wörter im <a href="?page=dictionary">Wörterbuch</a><br/>
	[<a href="?page=news">Archiv</a>] -->

	<!--<div class="accordion" id="accordionExample">
		<div class="accordion-item">
			<h2 class="accordion-header">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				Neuigkeiten
				</button>
			</h2>
			<div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					2023-12-31 - Release <a href="?page=projects_mc">Minecraft Übersetzung v5</a><br/>
					2023-12-20 - 2100 Wörter im <a href="?page=dictionary">Wörterbuch</a><br/>
				</div>
			</div>
		</div>
		<div class="accordion-item">
			<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				FAQ
				</button>
			</h2>
			<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					What is this, can I use it, how can I contact you, … bla blub
				</div>
			</div>
		</div>
		<div class="accordion-item">
			<h2 class="accordion-header">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				Accordion Item #3
				</button>
			</h2>
			<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
				<div class="accordion-body">
					<strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
				</div>
			</div>
		</div>
	</div>-->
</div>