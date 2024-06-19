<div class="container">
	<div class="row">
		<div class="col-12">
		<h1>Stats</h1>
		<h2>Words by author</h2>
		<?php
		$sql = "SELECT author, COUNT(*) AS count FROM words WHERE verifyStatus>0 GROUP BY author ORDER BY count DESC;";
		$ret = $db->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
			echo '<p><a href="?page=author&id='.$row['author'].'">'.($row['author']).'</a>: '.$row['count'].'</p>';
		}
		?>
		<h2>Translations by author</h2>
		<?php
		$sql = "SELECT author, COUNT(*) AS count FROM translations GROUP BY author ORDER BY count DESC;";
		$ret = $db->query($sql);
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
			echo '<p><a href="?page=author&id='.$row['author'].'">'.($row['author']).'</a>: '.$row['count'].'</p>';
		}
		?>
		<h2>Words per half year</h2>
		<?php
		//get the data
		$sql = "SELECT date from words WHERE verifyStatus>0 ORDER BY date ASC;";
		$ret = $db->query($sql);
		$out = array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC)){
			$dt = $row['date'];
			if(strlen($dt) >= 7) {//begins yyyy-mm
				$dt = substr($dt, 0, 7);
				//convert to key YYYY-H where H is the year half (0 for months 1-6, 1 for months 7-12)
				$dt = substr($dt, 0, 4)."-".(substr($dt, 5, 2) > 6 ? 1 : 0);
				if(!isset($out[$dt])){
					$out[$dt] = 0;
				}
				++$out[$dt];
			} else {
				//it belongs to first half of 2022
				$dt = "2022-0";
				if(!isset($out[$dt])){
					$out[$dt] = 0;
				}
				++$out[$dt];
			}
		}
		//get the labels for the graph sorted
		$labels = array_keys($out);
		usort($labels, function($a, $b){
			$a = explode("-", $a);
			$b = explode("-", $b);
			if($a[0] != $b[0]){
				return $a[0] - $b[0];
			}
			return $a[1] - $b[1];
		});
		//get the data for the graph in the same order
		$data = array();
		foreach($labels as $label){
			$data[] = $out[$label];
		}
		?>
		<div style="position:relative;"><canvas id="myChart" width="800" height="700"></canvas></div>
    <script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {labels: [<?php echo '"'.implode('","', $labels).'"'; ?>],
        datasets: [{
            label: 'Zeilen',
            data: [<?php echo implode(",", $data); ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255,99,132,1)'
        }]},
        options: {scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
    });
    </script>

	<h2>Letter distribution</h2>
	<?php
	$sql = "SELECT word FROM words WHERE verifyStatus>0;";
	$ret = $db->query($sql);
	$letters = array();
	while($row = $ret->fetchArray(SQLITE3_ASSOC)){
		$word = $row['word'];
		for($i = 0; $i < strlen($word); ++$i){
			$letter = $word[$i];
			if(!isset($letters[$letter])){
				$letters[$letter] = 0;
			}
			++$letters[$letter];
		}
	}
	ksort($letters);
	?>
	<div style="position:relative;"><canvas id="letterChart" width="800" height="700"></canvas></div>
	<script>
	var ctx = document.getElementById("letterChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {labels: [<?php echo '"'.implode('","', array_keys($letters)).'"'; ?>],
		datasets: [{
			label: 'Letters',
			data: [<?php echo implode(",", array_values($letters)); ?>],
			backgroundColor: 'rgba(255, 99, 132, 0.2)',
			borderColor: 'rgba(255,99,132,1)'
		}]},
		options: {scales: {yAxes: [{ticks: {beginAtZero:true}}]}}
	});
	</script>
	</div>
</div>