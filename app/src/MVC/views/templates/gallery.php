<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v3.1&appId=757017024649507';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="gallery" tabindex="-1">
  <div class="myModal" id="<?php echo $data['picture']; ?>">
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
      <div class="fb-share-button" data-layout="button" data-size="small" data-mobile-iframe="true"><a target="_blank" href="" class="fb-xfbml-parse-ignore">Partager</a></div>
      <div class="comments">
        <h2 class="comments-title">COMMENTS</h2>
        <div class="comments-body"></div>
        <div class="comments-add">
          <textarea title="Press <TAB> to send new comment (500 char. max)." class="comment-area" name="comments" placeholder="Press <TAB> to send new comment (500 char. max)." required></textarea>
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