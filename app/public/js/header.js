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
