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
let close = document.querySelectorAll(".close");
let form_key = document.querySelector("#form_key");



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
for (let i = 0; i < frames.length; i++)
{
	frames[i].setAttribute('draggable', true);
	
	frames[i].addEventListener('dragstart', drag);

	frames[i].addEventListener('dragenter', function(ev) {
		dropzones.forEach(function(dropzone){
			layer = document.createElement('div');
			layer.className = "dropzone-layer";
			dropzone.insertAdjacentElement('afterbegin', layer);
		});
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
for (let i = 0; i < dropzones.length; i++)
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
		clone.draggable = false;
		picture_taken.appendChild(clone);
	});
	// make picture appear with associated frames
	picture_taken.style.opacity = 1;
	save_picture.style.opacity = 1;
	photo.setAttribute('src', data);
	save_picture.addEventListener('click', savepicture);
	save_picture.style.cursor = 'pointer';
}
take_picture.addEventListener('click', takepicture);



// AJAX
function savepicture()
{	
	let data_frames = [];
	let frames = picture_taken.querySelectorAll(".frames").forEach(function(frame){
		data_frames.push({"name" : frame.id, "left" : frame.style.left, "top" : frame.style.top});
	});
	let data = "base64data=" + encodeURIComponent(photo.src)
	+ "&frames=" + JSON.stringify(data_frames)
	+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();

	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200)
			{
				data = JSON.parse(httpRequest.response);
				form_key.value = data['csrf'];
				if (control_ajax_return(data))
					return;

				let pic = document.createElement('div');
				pic.id = data['picture_id'];
				pic.className = "small-pic-container";

				let img = document.createElement('img');
				img.className = "small-pic";
				img.src = data['src'];

				let close = document.createElement('a');
				close.className = "close";
				close.addEventListener('click', deletepicture);

				pic.append(img, close);
				my_pics.firstChild.remove();
				my_pics.insertAdjacentElement('afterbegin', pic);
				save_picture.style.opacity = 0;
				save_picture.style.cursor = 'default';
				save_picture.removeEventListener('click', savepicture);
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", 'save', true);
	httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function deletepicture(ev)
{
	if (confirm("Are you sure you want to delete this picture ?"))
	{
		let elem = ev.currentTarget.parentElement;
		let data = "picture_id=" + elem.id
		+ "&form_key=" + form_key.value;

		let httpRequest = new XMLHttpRequest();

		httpRequest.onreadystatechange = function() {
			if (httpRequest.readyState === XMLHttpRequest.DONE) {
				if (httpRequest.status === 200)
				{
					data = JSON.parse(httpRequest.response);
					form_key.value = data['csrf'];
					if (control_ajax_return(data))
						return;
					document.querySelector("#pic" + data['picture_id']).remove();
					
				}
				else
					flash("Internal problem. Please contact admin.")
			}
		};

		httpRequest.open("POST", 'delete', true);
		httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		httpRequest.send(data);
	}
}
for (let i = 0; i < close.length; i++)
	close[i].addEventListener('click', deletepicture);