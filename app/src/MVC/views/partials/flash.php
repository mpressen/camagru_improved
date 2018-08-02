<?php 
if (isset($_SESSION['error']))
	echo "<div id='flash' class='flash-message'>".$_SESSION['error']."</div>";
unset($_SESSION['error']);