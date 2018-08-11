<?php 

class Mailer
{
	// public function __construct()
	// {

	// }

	public function send_confirmation($login, $mail, $confirmkey)
	{	
		$message = "<html><body><p>Hello ".$login.",<br>click on this link to activate your account :<br><a href='http://".$_SERVER['HTTP_HOST']."/user/confirm?login=".$login."&confirmkey=".$confirmkey."'>Activate your account</a></body></html>";
		$sujet = "Welcome to Camagruuuuu!";
		$header = "From: Camagru<camagru.mpressen@gmail.com>\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		mail($mail, $sujet, $message, $header);
	}
}