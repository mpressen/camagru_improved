<header class="header">
	<nav>
		<ul class="header-nav">
			<li><a href="/"
				<?php 
				if ($data['title'] == "Home")
					echo " class='active-link'"
				?>
				>HOME</a></li>
				<li><a href="/user/signin/"
					<?php 
					if ($data['title'] == "Sign In")
						echo " class='active-link'"
					?>
					>SIGN IN</a></li>
					<li><a href="/user/signup/"
						<?php 
						if ($data['title'] == "Sign Up")
							echo " class='active-link'"
						?>
						>SIGN UP</a></li>
					</ul>
				</nav>
				<div class="sandwitch" onclick="sandwitch(this)">
					<div class="bar1"></div>
					<div class="bar2"></div>
					<div class="bar3"></div>
				</div>
				<a href="/" class="header-logo">Camagruuuuuuuu </a>
			</header>