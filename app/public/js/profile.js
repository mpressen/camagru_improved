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

		let httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				form_key.value = data['key'];
				if (data['field'])
					document.querySelector("#login").value = data['field'];
				control_ajax_return(data);
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

		httpRequest.open("POST", "/user/update");
		httpRequest.send(formData);
	}
	else
		window.location.replace("/user/profile");
}