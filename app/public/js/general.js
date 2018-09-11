function sandwitch(x) {
	document.querySelector(".header-nav").classList.toggle("appear");
	x.classList.toggle("change");
}

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
		flash(data['message']);
		return true;
	}
	return false;
}

// mobile not compatible with webcam
if (/Mobi|Android/i.test(navigator.userAgent)) {
	let worktab = document.querySelector('#workshop-link');
	if (worktab)
	{
		worktab.style.cursor = 'not-allowed';
		worktab.href = '#';
		worktab.addEventListener('click', function() {flash('Workshop feature only available on Desktop.')});
	}
}