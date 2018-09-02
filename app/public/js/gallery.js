// Get the modal
let modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
let span = document.getElementsByClassName("close2")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function show_modal(ev)
{
	modal.style.display = "block";
}

let pic = document.querySelectorAll(".gallery-picture-container");
for (var i = 0; i < pic.length; i++)
	pic[i].addEventListener('click', show_modal);