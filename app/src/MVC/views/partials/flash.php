<?php 
if (isset($_SESSION['message']))
	echo "<div id='flash' class='flash-message'>".$_SESSION['message']."</div>";
unset($_SESSION['message']);