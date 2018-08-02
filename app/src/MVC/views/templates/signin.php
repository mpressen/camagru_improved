<div class="form-container">
	<form action="/user/connect" method="POST">
		<div class="inputs">
			<label for="mail">Email :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<input type="email" name="mail" autofocus>
		</div>
		<div class="inputs">
			<label for="pwd">Password :</label>
			<div class="tooltip">
				<input type="password" name="pwd">
				<span class="forgotten"><a href="/auth/reset">forgotten password ?</a></span>
			</div>
		</div>
		<div class="inputs">
			<input type="submit" value="Submit">
		</div>
	</form>
</div>