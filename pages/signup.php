<div class="container">
	<div class="row">
		<div class="col-12">
		<h1>Sign up</h1>

		<?php if(!isset($_POST['submit'])){ ?>
		<form action="?page=signup" method="POST">
			<div class="form-group row">
				<label for="uname" class="col-4 col-form-label">Username</label> 
				<div class="col-8">
					<input id="uname" name="uname" type="text" required="required" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="pwd" class="col-4 col-form-label">Password</label> 
				<div class="col-8">
					<input id="pwd" name="pwd" type="password" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="dneg" class="col-4 col-form-label">Display name (english and german)</label> 
				<div class="col-8">
					<input id="dneg" name="dneg" type="text" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="dniioo" class="col-4 col-form-label">Display name (iiooqas)</label> 
				<div class="col-8">
					<input id="dniioo" name="dniioo" placeholder="use above display name in lowercase when unsure" type="text" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-4"></label> 
				<div class="col-8">
					<div class="custom-control custom-checkbox custom-control-inline">
						<input name="dpcheck" id="dpcheck_0" type="checkbox" required="required" class="custom-control-input" value="dpcheck1"> 
						<label for="dpcheck_0" class="custom-control-label">I agree to this data being saved according to the data protection guidelines</label>
					</div>
				</div>
			</div> 
			<div class="form-group row">
				<div class="offset-4 col-8">
					<button name="submit" type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
		<?php } else {
			createAuthor($_POST['uname'], $_POST['pwd'], $_POST['dneg'], $_POST['dniioo']);
			echo "<p>Created successfully. You can log in now.</p>";
		} ?>
	</div>
</div>