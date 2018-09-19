function scrolling() {
	let gallery = document.querySelector(".gallery");
	if (!scroll || parseInt(gallery.scrollTop / ref) > 0)
		load_more_pictures();
}

function load_pictures_count() {
	switch (true) {
		case (window.innerWidth <= 502):
			return 1;
		case (window.innerWidth <= 1004):
			return 2;
		case (window.innerWidth <= 1506):
			return 3;
		case (window.innerWidth <= 2008):
			return 4;
		default:
			return 5;
	}
}

function load_more_pictures() {
	ref += 360 - (scroll * 30);
	scroll += 1;
	let gallery = document.querySelector(".gallery");
	gallery.removeEventListener("scroll", _scroll);
	let images_on_row = load_pictures_count();
	let sum = images_on_row - ((parseInt(gallery.childElementCount) - 1) % images_on_row);

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);
				data['pictures'].forEach( picture => {
					let picture_div = document.createElement('div');
					picture_div.id = "pic" + picture.id;
					picture_div.className = "gallery-picture-container";

					let moar_picture = document.createElement('img');
					moar_picture.className = "gallery-photo";
					moar_picture.src = picture.path;

					picture_div.append(moar_picture);
					gallery.append(picture_div);

					gallery.addEventListener("scroll", _scroll);
					picture_div.addEventListener('click', () => { show_modal(); });
				});
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("GET", '/home/infinite?picture_id=' + gallery.lastElementChild.id + '&load_count=' + sum, true);
	httpRequest.send();
};

function reset_modal() {
	let modal = document.querySelector('.myModal');
	modal.style.display = "none";

	let like = document.querySelector(".fa-thumbs-up");
	like.style.color = 'black';
	like.removeEventListener('click', _add_like);
	like.removeEventListener('click', _remove_like);

	let comments_body = document.querySelector(".comments-body");
	while (comments_body.firstChild) {
		comments_body.removeChild(comments_body.firstChild);
	}

	let input = document.querySelector(".comment-area");
	input.value = "";
}

function show_modal(event) {
	let modal = document.querySelector('.myModal');
	let pic_container_id = modal.id ? modal.id : event.currentTarget.id;
	modal.id = '';

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = () => {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);

				let count_likes = document.querySelector(".count-likes");
				count_likes.innerHTML = data['count'];

				let owner_profile = document.querySelector(".owner-profile");
				owner_profile.src = 'https://www.gravatar.com/avatar/' + data['owner_profile'] + "?d=mp";
				owner_profile.title = data['owner_login'];
				owner_profile.id = 'comment' + pic_container_id;

				let photo = document.querySelector(".photo-modal");
				photo.src = data['image_path'];

				let og_image = document.querySelector("meta[name=og_image]");
				og_image.content = "https://www.camagru.maximilien-pressense.fr" + data['image_path'];

				let like = document.querySelector(".fa-thumbs-up");
				let input = document.querySelector(".comment-area");
				let button_comment = document.querySelector("#comment-button");
				if (data['auth']) {
					input.addEventListener("keydown", () => { change_comment(); });
					button_comment.addEventListener('click', () => { post_comment(); });
					if (!data['auth_like']) {
						like.id = "like" + pic_container_id;
						like.addEventListener('click', _add_like);
					} else {
						like.style.color = '#ed6e2f';
						like.id = "like" + data['auth_like'];
						like.addEventListener('click', _remove_like);
					}
				} else {
					like.style.cursor = 'not-allowed';
					input.style.cursor = 'not-allowed';
					button_comment.style.cursor = 'not-allowed';
					like.addEventListener('click', function () { flash('You must log in first.') });
					input.addEventListener('click', function () { flash('You must log in first.') });
					button_comment.addEventListener('click', function () { flash('You must log in first.') });
				}
				data['comments'].forEach(comment => {
					let comment_div = document.createElement('div');
					comment_div.className = "comment-div";

					let comment_pic = document.createElement('img');
					comment_pic.className = "owner-profile";
					comment_pic.src = 'https://www.gravatar.com/avatar/' + comment.owner_profile + "?d=mp";
					comment_pic.title = comment.owner_login;

					let comment_text = document.createElement('p');
					comment_text.className = "comment-text";
					comment_text.innerHTML = comment.text;

					let comment_time = document.createElement('p');
					comment_time.className = "comment-time";
					comment_time.innerHTML = comment.timestamp;
					comment_div.append(comment_pic, comment_text, comment_time);

					let comments_body = document.querySelector(".comments-body");
					comments_body.append(comment_div);
				});
				modal.style.display = "block";

			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("GET", '/home/modal?picture_id=' + pic_container_id, true);
	httpRequest.send();
}

// ajax
function add_like_picture() {
	let data = "picture_id=" + event.target.id
		+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);
				form_key.value = data['key'];

				if (control_ajax_return(data))
					return;

				let like = document.querySelector(".fa-thumbs-up");
				like.style.color = '#ed6e2f';
				like.id = "like" + data['like_id'];
				like.removeEventListener('click', _add_like);
				like.addEventListener('click', _remove_like);

				let count_likes = document.querySelector(".count-likes");
				count_likes.innerHTML = parseInt(count_likes.innerHTML) + 1;

			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/like', true);
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function remove_like_picture() {
	let data = "like_id=" + event.target.id
		+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);
				form_key.value = data['key'];

				if (control_ajax_return(data))
					return;

				let like = document.querySelector(".fa-thumbs-up");
				like.style.color = 'black';
				like.id = "like" + data['picture_id'];
				like.removeEventListener('click', _remove_like);
				like.addEventListener('click', _add_like);

				let count_likes = document.querySelector(".count-likes");
				count_likes.innerHTML = parseInt(count_likes.innerHTML) - 1;
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/dislike', true);
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function change_comment() {
	if (event.keyCode === 9) {
		post_comment();
	}
}

function post_comment() {
	let owner_profile = document.querySelector(".owner-profile");
	let input = document.querySelector(".comment-area");
	let data = "picture_id=" + owner_profile.id +
		"&comment=" + input.value +
		"&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);
				form_key.value = data['key'];

				if (control_ajax_return(data))
					return;

				let comment_pic = document.createElement('img');
				comment_pic.className = "owner-profile comment-pic";
				comment_pic.src = 'https://www.gravatar.com/avatar/' + data['owner_profile'] + "?d=mp";
				comment_pic.title = data['owner_login'];

				let comment_text = document.createElement('p');
				comment_text.className = "comment-text";
				comment_text.innerHTML = data['comment'];

				let comment_time = document.createElement('p');
				comment_time.className = "comment-time";
				comment_time.innerHTML = 'Just now';

				let comment_div = document.createElement('div');
				comment_div.className = "comment-div";
				comment_div.append(comment_pic, comment_text, comment_time);

				let comments_body = document.querySelector(".comments-body");
				comments_body.append(comment_div);
				comments_body.scrollTop = comments_body.scrollHeight;

				input.value = '';
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/comment', true);
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send(data);
}

let scroll = 0;
let ref = 0;

let _add_like = function () { add_like_picture(); }
let _remove_like = function () { remove_like_picture(); }
let _scroll = function () { scrolling(); }

document.addEventListener("DOMContentLoaded", function () {
	let gallery = document.querySelector(".gallery");
	let pics = document.querySelectorAll(".gallery-picture-container");
	let modal = document.querySelector('.myModal');
	let close = document.querySelector(".close");

	// infinite scrolling
	gallery.addEventListener("scroll", _scroll);

	// comment mail notification specific
	if (modal.id)
		show_modal(event);

	// allow direct gallery scrolling
	gallery.focus();

	// facebook sharing
	(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v3.1&appId=697366577108902&autoLogAppEvents=1';
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// modal
	close.onclick = () => { reset_modal(); }
	window.onclick = () => {
		if (event.target === modal)
			reset_modal();
	}

	pics.forEach(pic => {
		pic.addEventListener('click', () => { show_modal(event); });
	})
});
