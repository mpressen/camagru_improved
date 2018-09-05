let inputs = document.getElementsByClassName("js");
for (let i = 0; i < inputs.length; i++)
	inputs[i].addEventListener("change", update_field);

function update_field(ev)
{
	if (confirm("Please confirm your " + this.name + " update."))
	{
		let formData = new FormData();
		let form_key = document.querySelector("#form_key").value;

		formData.append("form_key", form_key);
		formData.append(this.name, this.value);

		let request = new XMLHttpRequest();
		request.addEventListener('load', function(event) {
			window.location.replace("/user/profile");
		});
		request.addEventListener('error', function(event) {
			flash('Oops! Something went wrong. Please retry later.');
		});	

		request.open("POST", "/user/update");
		request.send(formData);
	}
	else
		window.location.replace("/user/profile");
}