function update_field(event) {
	event.target.removeEventListener("change", _update);
	if (confirm("Please confirm your " + event.target.name + " update.")) {
		let formData = new FormData();
		let form_key = document.querySelector("#form_key");

		formData.append("form_key", form_key.value);
		formData.append(event.target.name, event.target.value);

		let httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function () {
			if (httpRequest.readyState === XMLHttpRequest.DONE) {
				if (httpRequest.status === 200) {
					let data = JSON.parse(httpRequest.response);
					form_key.value = data['key'];
					if (data['field'])
						document.querySelector("#login").value = data['field'];
					control_ajax_return(data);
				}
				else
					flash("Internal problem. Please contact admin.")
				event.target.addEventListener("change", _update);
			}
		};
		httpRequest.open("POST", "/user/update");
		httpRequest.send(formData);
	}
	else
		window.location.replace("/user/profile");
}

let _update = function () { update_field(event); }

document.addEventListener("DOMContentLoaded", function () {

	let inputs = document.querySelectorAll(".js");
	inputs.forEach(input => {
		input.addEventListener("change", _update);
	});
});
