<div class="form-container">
	<form>
		<?php echo $data['csrf']?>
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
		<a href="/user/reset_password">RESET PASSWORD</a>
	</form>
</div>

<script type="text/javascript">
	let inputs = document.getElementsByClassName("js");
	for (var i = 0; i < inputs.length; i++)
		inputs[i].addEventListener("change", test);

	function test(ev)
	{
		let formData = new FormData();
		let csrf = document.querySelector('input[name="form_key"]').value;

		formData.append("form_key", csrf);
		formData.append(this.name, this.value);

		var request = new XMLHttpRequest();

		request.addEventListener('load', function(event) {
			window.location.replace("/user/profile");
		});
		request.addEventListener('error', function(event) {
			alert('Oops! Something went wrong. Please retry later.');
		});	

		request.open("POST", "/user/update");
		request.send(formData);
	}
</script>