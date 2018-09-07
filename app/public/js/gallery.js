let modal = document.querySelector('.myModal');
let close = document.querySelector(".close");
let photo = document.querySelector(".photo-modal");
let like = document.querySelector(".fa-thumbs-up");
let count_likes = document.querySelector(".count-likes");
let owner_profile = document.querySelector(".owner-profile");
let input = document.querySelector(".comment-area");
let comments_body = document.querySelector(".comments-body");
let form_key = document.querySelector("#form_key");
let gallery = document.querySelector(".gallery");
let fcbk_button = document.querySelector(".fb-share-button");
// let fcbk_link = document.querySelector(".fb-xfbml-parse-ignore");
let og_url = document.querySelector("meta[name=og_url]");
let og_image = document.querySelector("meta[name=og_image]");

if (modal.id)
	show_modal();


gallery.focus();

// facebook sharing
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v3.1&appId=697366577108902&autoLogAppEvents=1';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// infinite scrolling
let scroll = 0;
let ref = 0;
gallery.addEventListener("scroll", scrolling);

function scrolling() 
{
	if (!scroll || parseInt(this.scrollTop / ref) > 0)
		load_more_pictures();
}

function load_pictures_count()
{
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

function load_more_pictures()
{
	ref += 360 - (scroll * 30);
	scroll += 1;
	gallery.removeEventListener("scroll", scrolling);
	let images_on_row = load_pictures_count();
	let sum = images_on_row - ((parseInt(gallery.childElementCount) - 1) % images_on_row);
	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{	
				data = JSON.parse(httpRequest.response);
				data['pictures'].forEach(function(picture){
					let picture_div = document.createElement('div');
					picture_div.id = "pic" + picture.id;
					picture_div.className = "gallery-picture-container";

					let moar_picture = document.createElement('img');
					moar_picture.className = "gallery-photo";
					moar_picture.src = picture.path;
					
					picture_div.append(moar_picture);
					gallery.append(picture_div);
					gallery.addEventListener("scroll", scrolling);
					picture_div.addEventListener('click', show_modal);

				});
				

			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("GET", '/home/infinite?picture_id=' + gallery.lastElementChild.id + '&load_count=' + sum, true);
	httpRequest.send();
};


// modal
close.onclick = function() { reset_modal(); }

window.onclick = function(event) {
	if (event.target == modal)
		reset_modal();
}

function reset_modal(ev)
{
	modal.style.display = "none";
	like.style.color = 'black';
	like.removeEventListener('click', add_like_picture);
	like.removeEventListener('click', remove_like_picture);
	while (comments_body.firstChild) {
		comments_body.removeChild(comments_body.firstChild);
	}
}

function show_modal(ev)
{	
	let pic_container_id = modal.id ? modal.id : ev.currentTarget.id;
	modal.id = '';
	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				count_likes.innerHTML = data['count'];
				owner_profile.src = 'https://www.gravatar.com/avatar/' + data['owner_profile'] + "?d=mp";
				owner_profile.title = data['owner_login'];
				photo.src        = data['image_path'];
				og_image.content = "https://www.camagru.maximilien-pressense.fr" + data['image_path'];
				owner_profile.id = 'comment' + pic_container_id;
				let int_id = pic_container_id.substr(3);
				fcbk_button.dataset.href = "https://www.camagru.maximilien-pressense.fr/home/index?picture_id=" + int_id;
				og_url.content           = "https://www.camagru.maximilien-pressense.fr/home/index?picture_id=" + int_id;
				// fcbk_link.href = "https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcamagru.maximilien-pressense.fr%2Fhome%2Findex%3Fpicture_id%253F" + int_id + "&amp;src=sdkpreparse"

				if (data['auth'] && !data['auth_like'])
				{
					like.addEventListener('click', add_like_picture);
					input.addEventListener("change", post_comment);
					like.id = "like" + pic_container_id;
				}
				else if (data['auth'] && data['auth_like'])
				{
					like.addEventListener('click', remove_like_picture);
					input.addEventListener("change", post_comment);
					like.style.color = '#ed6e2f';
					like.id = "like" + data['auth_like'];
				}
				else
				{
					like.addEventListener('click', function() {flash('You must log in first.')});
					input.addEventListener('click', function() {flash('You must log in first.')});
					like.style.cursor = 'not-allowed';
					input.style.cursor = 'not-allowed';
				}
				data['comments'].forEach(function(comment){
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
let pic = document.querySelectorAll(".gallery-picture-container");
for (let i = 0; i < pic.length; i++)
	pic[i].addEventListener('click', show_modal);


// ajax
function add_like_picture(ev)
{
	let data = "picture_id=" + ev.currentTarget.id
	+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				
				form_key.value = data['key'];
				
				if (control_ajax_return(data))
					return;
				
				like.style.color = '#ed6e2f';
				like.id = "like" + data['like_id'];
				count_likes.innerHTML = parseInt(count_likes.innerHTML) + 1;
				like.removeEventListener('click', add_like_picture);
				like.addEventListener('click', remove_like_picture);

			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/like', true);
	httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function remove_like_picture(ev)
{
	let data = "like_id=" + ev.currentTarget.id
	+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				
				form_key.value = data['key'];
				
				if (control_ajax_return(data))
					return;
				
				like.style.color = 'black';
				like.id = "like" + data['picture_id'];
				count_likes.innerHTML = parseInt(count_likes.innerHTML) - 1;
				like.removeEventListener('click', remove_like_picture);
				like.addEventListener('click', add_like_picture);
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/dislike', true);
	httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function post_comment(ev)
{	
	let data = "picture_id=" + owner_profile.id 
	+ "&comment=" + ev.currentTarget.value 
	+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);

				form_key.value = data['key'];
				
				if (control_ajax_return(data))
					return;
				
				let comment_div = document.createElement('div');
				comment_div.className = "comment-div";

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

				comment_div.append(comment_pic, comment_text, comment_time);
				// comments_body.insertAdjacentElement('afterbegin', comment_div);
				comments_body.append(comment_div);
				comments_body.scrollTop = comments_body.scrollHeight;
				input.value = '';
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", '/picture/comment', true);
	httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	httpRequest.send(data);
}