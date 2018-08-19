<div class="form-container">
	<form action="/user/reset_3" method="POST">
		<?php echo $data['csrf']?>
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