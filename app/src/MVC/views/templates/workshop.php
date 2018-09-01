<div class="frame-container dropzones" id="frame-container">
	<img class="frames cat-wtf" src="/images/frames/cat-wtf.png" id='cat-wtf'>
	<img class="frames hair" src="/images/frames/hair.png" id='hair'>
	<img class="frames pipe" src="/images/frames/pipe.png" id='pipe'>
	<img class="frames pikachu" src="/images/frames/pikachu.png" id='pikachu'>
	<img class="frames vegeta" src="/images/frames/vegeta.png" id='vegeta'>
</div>
<div class="workshop-container">
	<div class="centered">
		<div class="preview-container dropzones" id="preview-container">
			<video autoplay="true" id="preview-element" class="preview-element">
			</video>
		</div>
		<a id="take-picture" class="button take-picture">TAKE PICTURE</a>
		<div id="picture_taken" class="preview-container picture_taken" id="preview-container">
			<img id="photo" class="photo">
		</div>
		<a id="save-picture" class="button save-picture pressed-button">SAVE PICTURE</a>

	</div>
</div>
<div class="pictures-container">
	<?php 
	foreach($data['user']->get_pictures() as $picture)
	{
		echo '<div id="pic'.$picture->get_id().'" class="small-pic-container">';
		echo '<img class="small-pic" src="'.$picture->get_path().'">';
		echo '<a class="close"></a>';
		echo '</div>';
	} ?>
</div>