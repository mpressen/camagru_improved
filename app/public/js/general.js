function sandwitch(x) {
	document.querySelector(".header-nav").classList.toggle("appear");
	x.classList.toggle("change");
}

// Helper: flash message
function flash (text)
{
	let flash = document.createElement("div");
	flash.className = "flash-message";
	flash.innerHTML = text;
	document.body.insertAdjacentElement('afterbegin', flash);
}

function control_ajax_return(data)
{
	if (data['message'])
	{	
		if (data['message'] === 'validation')
			flash('Invalid input.');
		else if (data['message'] === 'csrf')
			flash('CSRF PROTECTION ACTIVATED.');
		else
			flash(message);
		return true;
	}
	return false;
}