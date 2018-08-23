<div class="form-container">
	<form>
		<?php echo $data['csrf']?>
		<?php echo "<a href='https://fr.gravatar.com/'><img class='big-profile' src='https://www.gravatar.com/avatar/".$data['user']->get_gravatar_hash()."?d=mp&s=280'></a>" ?>
		<div class="inputs">
			<label for="login">Login :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<div class="tooltip">
				<input class='js' type="text" name="login" value="<?php echo $data['user']->get_login() ?>">
				<span class="tooltiptext">Only letters, numbers and underscores allowed. 3 chars min.</span>
			</div>
		</div>
		<div class="inputs">
			<label for="mail">Email :&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
			<input class='js' type="email" name="mail" value="<?php echo $data['user']->get_mail() ?>">
		</div>
		<a class="reset-pwd" href="/user/reset_password">RESET PASSWORD</a>
	</form>
</div>