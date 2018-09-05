<?php 

class Mailer
{
	public function send_confirmation($user)
	{	
		$message = "
		<html>
		  <body>
		    <p> Hello ".$user->get_login().",
		      <br>
		        click on this link to activate your account :
		      <br>
		      <a href='".$_SERVER['HTTP_ORIGIN']."/user/confirm?login=".$user->get_login()."&confirmkey=".$user->get_confirmkey()."'>
			  	Activate your account
			  </a>
		  </body>
		</html>";
		$sujet = "Welcome to Camagruuuuu!";
		$header = "From: Camagru<camagru.mpressen@gmail.com>\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		mail($user->get_mail(), $sujet, $message, $header);
	}

	public function reset_password($user)
	{	
		$message = "
		<html>
		  <body>
		    <p> Hello ".$user->get_login().",
		      <br>
		        click on this link to set up a new password :
		      <br>
		      <a href='".$_SERVER['HTTP_ORIGIN']."/user/reset_2?login=".$user->get_login()."&confirmkey=".$user->get_confirmkey()."'>
			  	Reset your password
			  </a>
		  </body>
		</html>";
		$sujet = "Reset your password";
		$header = "From: Camagru<camagru.mpressen@gmail.com>\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		mail($user->get_mail(), $sujet, $message, $header);
	}

	public function comment_received($user, $picture)
	{	
		$message = "
		<html>
		  <body>
		    <p> Hello ".$user->get_login().",
		      <br>
		        Someone has just commented one of your picture :
		      <a href='".$_SERVER['HTTP_ORIGIN']."/home/index?picture_id=".$picture->get_id()."'>
			  	Go to your picture
			  </a>
		  </body>
		</html>";
		$sujet = "You received a new comment";
		$header = "From: Camagru<camagru.mpressen@gmail.com>\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		mail($user->get_mail(), $sujet, $message, $header);
	}
}