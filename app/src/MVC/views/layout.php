<!DOCTYPE html>
<html>
<?php require_once($this->path."partials/head.php") ?>
<body>
	<?php require_once($this->path."partials/header.php") ?>
	<?php require_once($this->path."partials/flash.php") ?>
	<div class="content-container">
		<?php require_once($this->path."templates/".$template) ?>
	</div>
	<?php require_once($this->path."partials/footer.php") ?>
	<script src="/js/header.js"></script>
	<?php 
	if (isset($data['title']) && $data['title'] === 'Workshop')
		echo '<script src="/js/workshop.js"></script>';
	else if (isset($data['title']) && $data['title'] === 'My profile')
		echo '<script src="/js/profile.js"></script>';
	?>
</body>
</html>