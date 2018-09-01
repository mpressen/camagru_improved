let shutter = new Audio('/sounds/shutter.mp3');

let workshop = document.querySelector(".workshop-container");

let preview = document.querySelector("#preview-container");
let video = document.querySelector("#preview-element");
let take_picture = document.querySelector("#take-picture");

let picture_taken = document.querySelector("#picture_taken");
let photo = document.querySelector("#photo");
let save_picture = document.querySelector("#save-picture");

let my_pics = document.querySelector(".pictures-container");

let frames = document.querySelectorAll(".frames");
let dropzones = document.querySelectorAll(".dropzones");



// responsiveness
function screenTest(e) {
	let frames = workshop.querySelectorAll(".frames");
	frames.forEach(function(frame){
		if (e.matches) {
			x = parseInt(frame.style.left) / 2;
			y = parseInt(frame.style.top) / 2;
		}
		else {
			x = parseInt(frame.style.left) * 2;
			y = parseInt(frame.style.top) * 2;
		}
		frame.setAttribute("style", "position: absolute; top: "+y+"px; left:"+x+"px;");	
	});
}
window.matchMedia('(max-width: 520px)').addListener(screenTest);


// Activate webcam stream
if (navigator.mediaDevices.getUserMedia) {       
	navigator.mediaDevices.getUserMedia({video: true})
	.then(function(stream) {
		video.srcObject = stream;
	})
	.catch(function(err0r) {
		flash("You must allow the use of your camera to use our app !");
	});
}


// Helper: flash message
function flash (text)
{
	let flash = document.createElement("div");
	flash.className = "flash-message";
	flash.innerHTML = text;
	document.body.insertAdjacentElement('afterbegin', flash);
}



// Drag'n Drop functions
function allowDrop(ev) 
{
	ev.preventDefault();
}
function drag(ev) 
{	
	ev.dataTransfer.setData("image_id", ev.target.id);
	ev.dataTransfer.setData("x-drag-offset", ev.offsetX);
	ev.dataTransfer.setData("y-drag-offset", ev.offsetY);
}
function drop(ev) 
{
	ev.preventDefault();
	let img = document.getElementById(ev.dataTransfer.getData("image_id"));
	ev.currentTarget.appendChild(img);

	let x = ev.offsetX - ev.dataTransfer.getData("x-drag-offset");
	let y = ev.offsetY - ev.dataTransfer.getData("y-drag-offset");

	img.setAttribute("style", "position: absolute; top: "+y+"px; left:"+x+"px;");
}
// 		Attach d'n'd events to proper elements and deal with :
// 		- drag'n'drop (extra tmp layer for precision tweak on frame positioning)
// 		- taking picture only if a frame is on the preview
for (var i = 0; i < frames.length; i++)
{
	frames[i].setAttribute('draggable', true);
	
	frames[i].addEventListener('dragstart', drag);

	frames[i].addEventListener('dragenter', function(ev) {
		layer = document.createElement('div');
		layer.className = "dropzone-layer";
		ev.target.parentElement.insertAdjacentElement('afterbegin', layer);
	});
	
	frames[i].addEventListener('dragend', function(ev) {
		document.querySelectorAll(".dropzone-layer").forEach(function(a){
			a.remove();});
		// check if a frame has been set in preview container
		let frameExist = preview.childElementCount - 1;
		if (frameExist)
		{
			take_picture.setAttribute("style", "cursor: pointer;");
			take_picture.classList.add("pressed-button");
		}

		else
		{
			take_picture.setAttribute("style", "cursor: not-allowed;");
			take_picture.classList.remove("pressed-button");
		}

	});
}
for (var i = 0; i < dropzones.length; i++)
{
	dropzones[i].addEventListener('drop', drop);
	dropzones[i].addEventListener('dragover', allowDrop);
}



// Take picture and add frames
function takepicture(ev) {
	let frameExist = preview.childElementCount - 1;
	if (!frameExist)
	{
		flash("You must drag'n'drop a frame on your webcam stream first !");
		return;
	}
	// play sound
	shutter.play();
	
	//draw picture
	let tmpcanvas = document.createElement("canvas");
	tmpcanvas.width = 502; 
	tmpcanvas.height = 376; 
	tmpcanvas.getContext('2d').drawImage(video, 0, 0, 502, 376);
	let data = tmpcanvas.toDataURL('image/png');

	// remove old frames
	picture_taken.querySelectorAll('.frames').forEach(function(frame){frame.remove();});
	// copy new frames
	preview.querySelectorAll('.frames').forEach(function(frame){
		let clone = frame.cloneNode(true);
		picture_taken.appendChild(clone);
	});
	// make picture appear with associated frames
	picture_taken.style.opacity = 1;
	save_picture.style.opacity = 1;
	photo.setAttribute('src', data);
}
take_picture.addEventListener('click', takepicture);



// AJAX: Send picture and frames properties to save it back-end
function savepicture()
{	
	let data_frames = [];
	let frames = picture_taken.querySelectorAll(".frames").forEach(function(frame){
		data_frames.push({"name" : frame.id, "left" : frame.style.left, "top" : frame.style.top});
	});
	let data = "base64data=" + encodeURIComponent(photo.src) + "&frames=" + JSON.stringify(data_frames);

	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				// data = JSON.parse(httpRequest.response);
				// alert(data['frames']);
				img = document.createElement('img');
				img.className = "small-pic";
				img.src = "data:image/png;base64," + httpRequest.responseText;
				my_pics.insertAdjacentElement('afterbegin', img);
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", 'save', true);
	httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	httpRequest.send(data);
}
save_picture.addEventListener('click', savepicture);



// 	function takepicture() {
// 		var tmpcanvas = document.createElement("canvas");
// 		tmpcanvas.width = 640; 
// 		tmpcanvas.height = 480; 
// 		tmpcanvas.getContext('2d').drawImage(video2, 0, 0, 640, 480);
// 		var data = tmpcanvas.toDataURL('image/png');
// 		var pic = "base64data=" + encodeURIComponent(data);
// 		var merging = new XMLHttpRequest();
// 		merging.onreadystatechange = function(){
// 			if (merging.readyState == 4 && (merging.status == 200 || merging.status == 0))
// 			{
// 				shutter.play();
// 				picture_taken.style.visibility = 'visible';
// 				photo.setAttribute('src', 'data:image/png;base64,' + merging.responseText);
// 			}
// 		};
// 		merging.open("POST",'ajax/mergepic.php', true);
// 		merging.setRequestHeader("Content-type","application/x-www-form-urlencoded");
// 		merging.send(pic + "&frame=" + frame_selected.innerHTML);
// 	}

// 	function savepicture()
// 	{
// 		var data = photo.src;
// 		var pic = "base64data=" + encodeURIComponent(data);
// 		var saving = new XMLHttpRequest();

// 		saving.onreadystatechange = function(){
// 			if (saving.readyState == 4 && (saving.status == 200 || saving.status == 0))
// 			{
// 				if (page == 1)
// 				{ 
// 					//creer card et integration dans le DOM
// 					var newcard = document.createElement("div");
// 					newcard.setAttribute('class', 'card');

// 					var newtitle = document.createElement("h3");
// 					newtitle.setAttribute('class', 'title_pic');
// 					newtitle.setAttribute('style', 'visibility:hidden');
// 					newtitle.innerHTML = "(Name)";
// 					newcard.appendChild(newtitle);

// 					var newpic = document.createElement("img");
// 					newpic.classList.add('picture_gallery');
// 					if (flip_or_not.innerHTML == 1)
// 						newpic.classList.add('flip');
// 					newpic.setAttribute('src', data);
// 					newcard.appendChild(newpic);

// 					var newspace = document.createElement("br");
// 					newcard.appendChild(newspace);

// 					var newnamebutton = document.createElement("button");
// 					newnamebutton.setAttribute('class', 'name');
// 					newnamebutton.setAttribute('name', 'name' + saving.responseText);
// 					newnamebutton.innerHTML = "Name it !";
// 					newcard.appendChild(newnamebutton);

// 					var newpublishbutton = document.createElement("button");
// 					newpublishbutton.setAttribute('class', 'publish');
// 					newpublishbutton.setAttribute('name', 'publish' + saving.responseText);
// 					newpublishbutton.innerHTML = "Unpublish";
// 					newcard.appendChild(newpublishbutton);

// 					var newsuppressbutton = document.createElement("button");
// 					newsuppressbutton.setAttribute('class', 'suppress');
// 					newsuppressbutton.setAttribute('name', 'suppress' + saving.responseText);
// 					newsuppressbutton.innerHTML = "Suppress";
// 					newcard.appendChild(newsuppressbutton);

// 					var newflipbutton = document.createElement("button");
// 					newflipbutton.setAttribute('class', 'flipbutton');
// 					newflipbutton.setAttribute('name', 'flip' + saving.responseText);
// 					newflipbutton.innerHTML = "Flip";
// 					newcard.appendChild(newflipbutton);

// 					var newspan = document.createElement("span");
// 					newspan.setAttribute('style', 'display:none;');
// 					newspan.innerHTML = saving.responseText;
// 					newcard.appendChild(newspan);
// 					my_pictures.insertBefore(newcard, my_pictures.firstChild);

// 					my_pictures.removeChild(pageobj);
// 					my_pictures.insertBefore(pageobj, my_pictures.firstChild);


// 					var childList = my_pictures.children[6];
// 					if (childList)
// 					{
// 						my_pictures.removeChild(childList);
// 						if (hidden_index)
// 							hidden_index.style.display = 'block';
// 						if (hidden_current)
// 						hidden_current.style.display = 'inline-block';
// 						if (hidden_next)
// 						hidden_next.style.display = 'inline-block';
// 						if (index)
// 						index.style.display = 'block';
// 						if (indexes)
// 						indexes[0].style.display = 'inline-block';
// 						if (indexes)
// 						indexes[1].style.display = 'inline-block';
// 					}

// 					newnamebutton.addEventListener('click', function(ev) {
// 							namepicture(this);
// 							ev.preventDefault();
// 						}, false);

// 					newpublishbutton.addEventListener('click', function(ev) {
// 							publishpicture(this);
// 							ev.preventDefault();
// 						}, false);

// 					newsuppressbutton.addEventListener('click', function(ev) {
// 							suppresspicture(this);
// 							ev.preventDefault();
// 						}, false);

// 					newflipbutton.addEventListener('click', function(ev) {
// 							flippicture(this);
// 							ev.preventDefault();
// 						}, false);
// 				}
// 				else
// 					document.location.href="play.php";
// 			}
// 		};

// 		saving.open("POST", 'ajax/savepic.php', true);
// 		saving.setRequestHeader("Content-type","application/x-www-form-urlencoded");
// 		saving.send(pic + '&flip=' + flip_or_not.innerHTML);
// 	}

// 	function namepicture(elem)
// 	{
// 		var button_name = elem.name;
// 		var element = document.getElementsByName(button_name)[0];
// 		var card = element.parentElement;
// 		var title  = card.firstChild;
// 		var name = prompt("Please enter a name for your picture", "");
// 		if (name !== null && name !== "")
// 		{
// 			var picture = card.lastChild;
// 			var renaming = new XMLHttpRequest();
// 			renaming.onreadystatechange = function()
// 			{
// 				if (renaming.readyState == 4 && (renaming.status == 200 || renaming.status == 0))
// 				{
// 					title.setAttribute("style", "visibility:visible;");
// 					title.innerHTML = name;
// 				}
// 			};
// 			renaming.open("POST",'ajax/renamepic.php', true);
// 			renaming.setRequestHeader("Content-type","application/x-www-form-urlencoded");
// 			renaming.send("name=" + name + "&picture_id=" + picture.innerHTML);
// 		}
// 	}

// 	for (var i = 0; i < namebutton.length; i++)
// 	{
// 		namebutton[i].addEventListener('click', function(ev) {
// 				namepicture(this);
// 				ev.preventDefault();
// 			}, false);
// 	}

// 	function suppresspicture(elem3)
// 	{
// 		if (confirm("Are you sure you want to delete this picture ?"))
// 		{
// 			var button_name3 = elem3.name;
// 			var element3 = document.getElementsByName(button_name3)[0];
// 			var card3 = element3.parentElement;
// 			var picture3 = card3.lastChild;
// 			var last_card = my_pictures.lastElementChild;
// 			var last_pic_id = last_card.lastChild.innerHTML;
// 			var suppressing = new XMLHttpRequest();
// 			suppressing.onreadystatechange = function()
// 				{
// 					if (suppressing.readyState == 4 && (suppressing.status == 200 || suppressing.status == 0))
// 					{
// 						my_pictures.removeChild(card3);
// 						var new_pic = suppressing.responseText.split(",");
// 						if (new_pic[0])
// 						{
// 							//creer card et integration dans le DOM
// 							var newcard = document.createElement("div");
// 							newcard.setAttribute('class', 'card');

// 							var newtitle = document.createElement("h3");
// 							newtitle.setAttribute('class', 'title_pic');
// 							if (new_pic[2])
// 								newtitle.innerHTML = new_pic[2];
// 							else
// 							{
// 								newtitle.setAttribute('style', 'visibility:hidden');
// 								newtitle.innerHTML = "(Name)";
// 							}
// 							newcard.appendChild(newtitle);

// 							var newpic = document.createElement("img");
// 							newpic.classList.add('picture_gallery');
// 							if (new_pic[4] == 1)
// 								newpic.classList.add('flip');
// 							newpic.setAttribute('src', 'pictures/' + new_pic[0]);
// 							newcard.appendChild(newpic);

// 							var newspace = document.createElement("br");
// 							newcard.appendChild(newspace);

// 							var newnamebutton = document.createElement("button");
// 							newnamebutton.setAttribute('class', 'name');
// 							newnamebutton.setAttribute('name', 'name' + new_pic[0]);
// 							newnamebutton.innerHTML = "Name it !";
// 							newcard.appendChild(newnamebutton);

// 							var newpublishbutton = document.createElement("button");
// 							newpublishbutton.setAttribute('class', 'publish');
// 							newpublishbutton.setAttribute('name', 'publish' + new_pic[0]);
// 							newpublishbutton.innerHTML = "Unpublish";
// 							newcard.appendChild(newpublishbutton);

// 							var newsuppressbutton = document.createElement("button");
// 							newsuppressbutton.setAttribute('class', 'suppress');
// 							newsuppressbutton.setAttribute('name', 'suppress' + new_pic[0]);
// 							newsuppressbutton.innerHTML = "Suppress";
// 							newcard.appendChild(newsuppressbutton);

// 							var newflipbutton = document.createElement("button");
// 							newflipbutton.setAttribute('class', 'flipbutton');
// 							newflipbutton.setAttribute('name', 'flip' + suppressing.responseText);
// 							newflipbutton.innerHTML = "Flip";
// 							newcard.appendChild(newflipbutton);

// 							var newspan = document.createElement("span");
// 							newspan.setAttribute('style', 'display:none;');
// 							newspan.innerHTML = new_pic[0];
// 							newcard.appendChild(newspan);
// 							my_pictures.appendChild(newcard);

// 							//rajout des events_listeners
// 							newnamebutton.addEventListener('click', function(ev) {
// 									namepicture(this);
// 									ev.preventDefault();
// 								}, false);

// 							newpublishbutton.addEventListener('click', function(ev) {
// 									publishpicture(this);
// 									ev.preventDefault();
// 								}, false);

// 							newsuppressbutton.addEventListener('click', function(ev) {
// 									suppresspicture(this);
// 									ev.preventDefault();
// 								}, false);

// 							newflipbutton.addEventListener('click', function(ev) {
// 									flippicture(this);
// 									ev.preventDefault();
// 								}, false);

// 							if (new_pic[5] != 1)
// 							{
// 								if (hidden_index)
// 									hidden_index.style.display = 'none';
// 								else if (index)
// 									index.style.display = 'none';
// 								if (hidden_current && hidden_next)
// 								{
// 									hidden_current.style.display = 'none';
// 									hidden_next.style.display = 'none';
// 								}
// 								else if (indexes)
// 								{
// 									indexes[0].style.display = 'none';
// 									indexes[1].style.display = 'none';
// 								}
// 							}
// 						}
// 						else if (page != 1 & my_pictures.children.length == 1)
// 						{
// 								var num = page - 1;
// 								document.location.href="play.php?page=" + num.toString();
// 						}
// 					}
// 				};
// 			suppressing.open("POST",'ajax/suppresspic.php', true);
// 			suppressing.setRequestHeader("Content-type","application/x-www-form-urlencoded");
// 			suppressing.send("picture_id=" + picture3.innerHTML +"&last_pic_id=" + last_pic_id);
// 		}
// 	}

// 	for (var k = 0; k < suppressbutton.length; k++)
// 	{
// 		suppressbutton[k].addEventListener('click', function(ev) {
// 				suppresspicture(this);
// 				ev.preventDefault();
// 			}, false);
// 	}


// 	startbutton.addEventListener('click', function(ev) {
// 			takepicture();
// 			ev.preventDefault();
// 		}, false);

// 	savebutton.addEventListener('click', function(ev){
// 			savepicture();
// 			ev.preventDefault();
// 		}, false);

// })();