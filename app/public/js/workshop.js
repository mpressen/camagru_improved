function screenTest(e) {
	let frames = workshop.querySelectorAll(".frames");
	frames.forEach(frame => {
		if (e.matches) {
			x = parseInt(frame.style.left) / 2;
			y = parseInt(frame.style.top) / 2;
		}
		else {
			x = parseInt(frame.style.left) * 2;
			y = parseInt(frame.style.top) * 2;
		}
		frame.setAttribute("style", "position: absolute; top: " + y + "px; left:" + x + "px;");
	});
}

// Drag'n Drop functions
function allowDrop() {
	event.preventDefault();
}

function drag() {
	event.dataTransfer.setData("image_id", event.target.id);
	event.dataTransfer.setData("x-drag-offset", event.offsetX);
	event.dataTransfer.setData("y-drag-offset", event.offsetY);
}

function drop() {
	event.preventDefault();
	let img = document.getElementById(event.dataTransfer.getData("image_id"));
	if (img && img.draggable == true) {
		event.currentTarget.appendChild(img);

		let x = event.offsetX - event.dataTransfer.getData("x-drag-offset");
		let y = event.offsetY - event.dataTransfer.getData("y-drag-offset");
		img.setAttribute("style", "position: absolute; top: " + y + "px; left:" + x + "px;");
	}
}

// Take picture and add frames
function takepicture() {
	let preview = document.querySelector("#preview-container");
	let frameExist = preview.childElementCount - 1;
	if (!frameExist) {
		flash("You must drag'n'drop a frame on your webcam stream first !");
		return;
	}
	// play sound
	let shutter = new Audio('/sounds/shutter.mp3');
	shutter.play();

	//draw picture
	let tmpcanvas = document.createElement("canvas");
	tmpcanvas.width = 502;
	tmpcanvas.height = 376;
	let video = document.querySelector("#preview-element");
	tmpcanvas.getContext('2d').drawImage(video, 0, 0, 502, 376);
	let data = tmpcanvas.toDataURL('image/png');

	// remove old frames
	let picture_taken = document.querySelector("#picture_taken");
	picture_taken.querySelectorAll('.frames').forEach(frame => { frame.remove(); });
	// copy new frames
	preview.querySelectorAll('.frames').forEach(frame => {
		let clone = frame.cloneNode(true);
		clone.draggable = false;
		picture_taken.appendChild(clone);
	});
	// make picture appear with associated frames
	let save_picture = document.querySelector("#save-picture");
	picture_taken.style.opacity = 1;
	save_picture.style.opacity = 1;
	let photo = document.querySelector("#photo");
	photo.setAttribute('src', data);
	save_picture.addEventListener('click', () => { savepicture(); });
	save_picture.style.cursor = 'pointer';
}



// AJAX
function savepicture() {
	let data_frames = [];
	let adapter = (window.innerWidth < 522 || window.innerHeight < 1050) ? 2 : 1;
	let picture_taken = document.querySelector("#picture_taken");
	let frames = picture_taken.querySelectorAll(".frames").forEach(function (frame) {
		data_frames.push({
			"name": frame.id,
			"left": parseInt(frame.style.left) * adapter,
			"top": parseInt(frame.style.top) * adapter
		});
	});
	let photo = document.querySelector("#photo");
	let form_key = document.querySelector("#form_key");
	let data = "base64data=" + encodeURIComponent(photo.src)
		+ "&frames=" + JSON.stringify(data_frames)
		+ "&form_key=" + form_key.value;

	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				data = JSON.parse(httpRequest.response);
				form_key.value = data['key'];
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
				close.addEventListener('click', () => { deletepicture(); });

				pic.append(img, close);
				let my_pics = document.querySelector(".pictures-container");
				my_pics.insertAdjacentElement('afterbegin', pic);
				let save_picture = document.querySelector("#save-picture");
				save_picture.style.opacity = 0;
				save_picture.style.cursor = 'default';
				save_picture.removeEventListener('click', () => { savepicture(); });
			}
			else
				flash("Internal problem. Please contact admin.")
		}
	};

	httpRequest.open("POST", 'save', true);
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send(data);
}

function deletepicture() {
	if (confirm("Are you sure you want to delete this picture ?")) {
		let elem = event.currentTarget.parentElement;
		let form_key = document.querySelector("#form_key");
		let data = "picture_id=" + elem.id
			+ "&form_key=" + form_key.value;

		let httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function () {
			if (httpRequest.readyState === XMLHttpRequest.DONE) {
				if (httpRequest.status === 200) {
					data = JSON.parse(httpRequest.response);
					form_key.value = data['key'];
					if (control_ajax_return(data))
						return;
					document.querySelector("#pic" + data['picture_id']).remove();
				}
				else
					flash("Internal problem. Please contact admin.")
			}
		};

		httpRequest.open("POST", 'delete', true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send(data);
	}
}


document.addEventListener("DOMContentLoaded", function () {
	// mobile not compatible with webcam
	if (/Mobi|Android/i.test(navigator.userAgent)) {
		location.href = '/home/index' + '?message=1';
	}

	// responsiveness
	let my_pics = document.querySelector(".pictures-container");
	my_pics.firstChild.remove();

	window.matchMedia('(max-width: 520px)').addListener(screenTest);

	// Activate webcam stream
	if (navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({ video: true })
			.then(function (stream) {
				let video = document.querySelector("#preview-element");
				video.srcObject = stream;
			})
			.catch(function (err0r) {
				flash("You must allow the use of your camera to use our app !");
			});
	}

	// 		Attach d'n'd events to proper elements and deal with :
	// 		- drag'n'drop (extra tmp layer for precision tweak on frame positioning)
	// 		- taking picture only if a frame is on the preview
	let frames = document.querySelectorAll(".frames");
	let dropzones = document.querySelectorAll(".dropzones");
	let take_picture = document.querySelector("#take-picture");
	frames.forEach(frame => {
		frame.setAttribute('draggable', true);

		frame.addEventListener('dragstart', () => { drag(); });

		frame.addEventListener('dragenter', function () {
			dropzones.forEach(dropzone => {
				let layer = document.createElement('div');
				layer.className = "dropzone-layer";
				dropzone.insertAdjacentElement('afterbegin', layer);
				dropzone.style.overflow = 'hidden';
			});
		});

		frame.addEventListener('dragend', function () {
			document.querySelectorAll(".dropzone-layer").forEach(a => {
				a.remove();
			});
			// check if a frame has been set in preview container
			let preview = document.querySelector("#preview-container");
			let frameExist = preview.childElementCount - 1;
			if (frameExist) {
				take_picture.setAttribute("style", "cursor: pointer;");
				take_picture.classList.add("pressed-button");
			}
			else {
				take_picture.setAttribute("style", "cursor: not-allowed;");
				take_picture.classList.remove("pressed-button");
			}

		});
	})

	dropzones.forEach(dropzone => {
		dropzone.addEventListener('drop', () => { drop(); });
		dropzone.addEventListener('dragover', () => { allowDrop(); });
	})

	take_picture.addEventListener('click', () => { takepicture(); });
	let closes = document.querySelectorAll(".close");
	closes.forEach(close => {
		close.addEventListener('click', () => { deletepicture(); });
	})
});
