let inputs = document.getElementsByClassName("js");
for (var i = 0; i < inputs.length; i++)
	inputs[i].addEventListener("change", update_field);

function update_field(ev)
{
	if (confirm("Please confirm your " + this.name + " update."))
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
	else
		window.location.replace("/user/profile");
}