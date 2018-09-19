<head>
	<?php
	if (isset($data['title']))
		echo "<title>".$data['title']." | Camagru</title>";
	else
		echo "<title>Camagru</title>";
	?>
	<link href="/images/favicon.png" rel="icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/general.js"></script>
	<?php
	if (isset($data['title']) && $data['title'] === 'Workshop')
		echo '<script src="/js/workshop.js"></script>';
	else if (isset($data['title']) && $data['title'] === 'My profile')
		echo '<script src="/js/profile.js"></script>';
	else if (isset($data['title']) && $data['title'] === 'Home')
		echo '<script src="/js/gallery.js"></script>';
	?>
	<!-- facebook sharing -->
	<meta property="og:url"           content="camagru.maximilien-pressense.fr" name="og_url" />
	<meta property="og:type"          content="website" />
	<meta property="og:title"         content="Camagru" />
	<meta property="og:description"   content="Dev project: webcam pictures sharing app" />
	<meta property="og:image"         content="https://www.camagru.maximilien-pressense.fr/images/favicon.png" name="og_image" />
</head>
