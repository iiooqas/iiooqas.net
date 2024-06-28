<?php
if(isset($_GET['id'])){
	$homeid = $_GET['id'];
} else if(isset($_SESSION['user'])){
	$homeid = $_SESSION['user'];
} else {
	die("can't find suitable home id");
}
?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Home</h1>
			<div class="row">
				<div class="col-md-6">
				<h2>Words by you</h2>
				<?php
				$sql = "SELECT * FROM words WHERE author=".$homeid." ORDER BY date DESC;";
				$ret = $db->query($sql);
				while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
					echo $row['date'].": ".'<a href="?page=word&word='.$row['word'].'">'.($row['word']).'</a><br/>';
				}
				?>
			</div>
			<div class="col-md-6">
				<h2>Translations by you</h2>
				<?php
				$sql = "SELECT * FROM translations WHERE author=".$homeid." ORDER BY date DESC;";
				$ret = $db->query($sql);
				while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
					echo $row['date'].": ".'<a href="?page=translation&id='.$row['id'].'">'.($row['translation']).'</a><br/>';
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>