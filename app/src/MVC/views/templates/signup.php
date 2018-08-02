<div class="form-container">
	<form action="/user/create" method="POST">
		<div class="inputs">
			<label for="login">Login :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<div class="tooltip">
				<input type="text" name="login" autofocus>
				<span class="tooltiptext">Only letters, numbers and underscores allowed. 3 chars min.</span>
			</div>
		</div>
		<div class="inputs">
			<label for="mail">Email :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<input type="email" name="mail">
		</div>
		<div class="inputs">
			<label for="pwd">Password :</label>
			<div class="tooltip">
				<input type="password" name="pwd">
				<span class="tooltiptext">Digit, upper and lower-case letters required. 8 chars min. </span>
			</div>
		</div>
		<div class="inputs">
			<input type="submit" value="Submit">
		</div>
	</form>
</div>