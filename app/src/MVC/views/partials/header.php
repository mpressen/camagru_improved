<header class="header">
	<nav>
		<ul class="header-nav">
			<li><a href="/"
				<?php 
				if ($data['title'] == "Home")
					echo " class='active-link'"
				?>
				>HOME
			</a></li>
			<?php
			if ($auth === false)
			{
				echo '<li><a href="/user/signin"';
				if ($data['title'] == "Sign In")
					echo " class='active-link'";
				echo ">SIGN IN</a></li>";
				echo '<li><a href="/user/signup"';
				if ($data['title'] == "Sign Up")
					echo " class='active-link'";
				echo ">SIGN UP</a></li>";
			}
			else
			{
				echo '<li><a href="/user/workshop"';
				if ($data['title'] == "Workshop")
					echo " class='active-link'";
				echo ">WORKSHOP</a></li>";
				echo '<li><a href="/user/signout"';
				echo ">SIGN OUT</a></li>";
			}
			?>
		</ul>
	</nav>
	<div class="sandwitch" onclick="sandwitch(this)">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
	</div>
	<a href="/" class="header-logo">Camagruuuuuuuu </a>
</header>