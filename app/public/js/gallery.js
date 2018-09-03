let modal = document.querySelector('.myModal');
let close = document.querySelector(".close");
let photo = document.querySelector(".photo-modal");
let like = document.querySelector(".fa-thumbs-up");
let count_likes = document.querySelector(".count-likes");
let owner_profile = document.querySelector(".owner-profile");

close.onclick = function() {
	modal.style.display = "none";
}
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
		like.style.color = 'black';
		like.removeEventListener('click', add_like_picture);
		like.removeEventListener('click', remove_like_picture);
	}
}

function show_modal(ev)
{	
	let pic_container = ev.currentTarget
	let pic = pic_container.firstElementChild;

	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				// console.log(data);
				count_likes.innerHTML = data['count'];
				owner_profile.src = 'https://www.gravatar.com/avatar/' + data['owner_profile'] + "?d=mp";
				owner_profile.title = data['owner_login'];

				if (data['auth'] && !data['auth_like'])
				{
					like.addEventListener('click', add_like_picture);
					like.id = "like" + pic_container.id;
				}
				else if (data['auth'] && data['auth_like'])
				{
					like.addEventListener('click', remove_like_picture);
					like.style.color = '#ed6e2f';
					like.id = "like" + data['auth_like'];
				}

				else
					like.style.cursor = 'not-allowed';
				photo.src = pic.src;
				modal.style.display = "block";

			}
			else
			{
				flash("Internal problem. Please contact admin.")
			}
		}
	};

	httpRequest.open("GET", 'home/modal?picture_id=' + pic_container.id, true);
	httpRequest.send();
}

let pic = document.querySelectorAll(".gallery-picture-container");
for (var i = 0; i < pic.length; i++)
	pic[i].addEventListener('click', show_modal);

function add_like_picture(ev)
{
	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				like.style.color = '#ed6e2f';
				like.id = "like" + httpRequest.responseText;
				count_likes.innerHTML = parseInt(count_likes.innerHTML) + 1;
				like.removeEventListener('click', add_like_picture);
				like.addEventListener('click', remove_like_picture);

			}
			else
			{
				flash("Internal problem. Please contact admin.")
			}
		}
	};

	httpRequest.open("GET", 'picture/like?picture_id=' + ev.currentTarget.id, true);
	httpRequest.send();
}

function remove_like_picture(ev)
{
	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				like.style.color = 'black';
				like.id = "like" + httpRequest.responseText;
				count_likes.innerHTML = parseInt(count_likes.innerHTML) - 1;
				like.removeEventListener('click', remove_like_picture);
				like.addEventListener('click', add_like_picture);
			}
			else
			{
				flash("Internal problem. Please contact admin.")
			}
		}
	};

	httpRequest.open("GET", 'picture/dislike?like_id=' + ev.currentTarget.id, true);
	httpRequest.send();
}