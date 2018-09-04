<span class="scrolled">0</span>
<div class="gallery" tabindex="-1">
  <div class="myModal">
    <div class="modal-content">
      <?php echo $data['csrf']?>
      <div class="modal-header">
        <div class="credential">
          <img class='owner-profile'>
        </div>
        <a class="close"></a>
      </div>
      <div class="modal-body">
        <div class="pic_container_modal">
          <img class="photo-modal">
        </div>
        <div class="like">
          <i class="fa fa-thumbs-up"></i>
          <integer class="count-likes"></integer>
        </div>
        <div class="comments">
          <h2 class="comments-title">COMMENTS</h2>
          <div class="comments-body"></div>
          <div class="comments-add">
            <textarea class="comment-area" name="comments" placeholder="New comment (500 char. max)" required></textarea>
          </div>
        </div>
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