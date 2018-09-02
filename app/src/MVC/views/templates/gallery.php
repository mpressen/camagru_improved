<div class="gallery">
<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close2">&times;</span>
      <h2>Modal Header</h2>
    </div>
    <div class="modal-body">
      <p>Some text in the Modal Body</p>
      <p>Some other text...</p>
    </div>
    <div class="modal-footer">
      <h3>Modal Footer</h3>
    </div>
  </div>

</div>
<?php 
foreach($data['pictures'] as $picture)
	{
		echo '<div id="pic'.$picture->get_id().'" class="gallery-picture-container">';
		echo '<img class="gallery-photo" src="'.$picture->get_path().'">';
		echo '</div>';
	}
?>
</div>