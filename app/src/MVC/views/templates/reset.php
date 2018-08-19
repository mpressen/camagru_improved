<div class="form-container">
	<form action="/user/reset_1" method="POST">
		<?php echo $data['csrf']?>
		<div class="inputs">
			<label for="mail">Email :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<input type="email" name="mail" autofocus>
		</div>
		<div class="inputs">
			<input type="submit" value="Submit">
		</div>
	</form>
</div>