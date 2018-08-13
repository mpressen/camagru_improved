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
		mail($user->mail, $sujet, $message, $header);
	}
}