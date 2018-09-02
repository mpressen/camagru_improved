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
			if ($data['user'])
			{
				echo '<li><a href="/picture/workshop"';
				if ($data['title'] == "Workshop")
					echo " class='active-link'";
				echo ">WORKSHOP</a></li>";
				echo "
				<li class='dropdown'>
					<img class='drop-trigger img-profile' src='https://www.gravatar.com/avatar/".$data['user']->get_gravatar_hash()."?d=mp'>
					<ul class='drop-menu'>
					<li><a href='/user/profile'";
					if ($data['title'] == "My profile")
					echo " class='active-link'";
				echo ">MY PROFILE</a></li>
					<li><a href='/user/signout'>SIGN OUT</a></li>
				</ul>
				</li>";
			}
			else
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