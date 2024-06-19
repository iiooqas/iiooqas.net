<div class="container">
	<div class="row">
		<div class="col-12">
		<h1>Login</h1>

		<?php if(!isset($_POST['submit'])){ ?>
		<form action="?page=login" method="POST">
			<div class="form-group row">
				<label for="uname" class="col-4 col-form-label">Username</label> 
				<div class="col-8">
					<input id="uname" name="uname" type="text" class="form-control" required="required">
				</div>
			</div>
			<div class="form-group row">
				<label for="pwd" class="col-4 col-form-label">Password</label> 
				<div class="col-8">
					<input id="pwd" name="pwd" type="password" class="form-control" required="required">
				</div>
			</div> 
			<div class="form-group row">
				<div class="offset-4 col-8">
					<button name="submit" type="submit" class="btn btn-primary">Log in</button>
				</div>
			</div>
		</form>
		<div class="col-12">
			<p><a href="?page=register">Register here</a> | <a href="index.php">Back</a></p>
		</div>
		<?php } else {
			if(verifyCredentials($_POST['uname'], $_POST['pwd'])){
				$_SESSION['user'] = unameToId($_POST['uname']);
				echo "<p>Logged in successfully.</p><script>function x(){window.location.href = 'index.php';}setTimeout(x,1000);</script>";
			} else {
				echo "<p>Wrong username or password.</p>";
			}
		} ?>
	</div>
</div>
<style>
nav {
	display: none !important;
}
.container {
	width: 550px;
	margin: auto auto;
	border: 3px solid black;
	border-radius: 10px;
	margin-top: 100px;
}
html[data-bs-theme="light"] .container {
	background-color: #f5f5f5;
}
html[data-bs-theme="dark"] .container {
	background-color: #545454;
}
h1 {
	text-align: center;
	font-size: 42px;
	margin-bottom: 15px;
}
button {
	margin-top: 8px;
	margin-bottom: 5px;
}
</style>