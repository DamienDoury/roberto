<?php
require_once "WebConversation/Abstract.php";
require_once "WebConversation/Post.php";
require_once "WebConversation/Get.php";
require_once "WebConversation/Conversation.php";
	
function connexion_cartosort($pseudo, $pass)
{
	$_SERVER["REQUEST_URI"] = "http://www.cartosort.com/logon.php";
	$_SERVER["SCRIPT_NAME"] = "http://www.cartosort.com/logon.php";

	$conversation = new Core_Request_Conversation();
	$request = $conversation->newPost("http://www.cartosort.com/logon.php");
	$request->setData("pseudo", $pseudo);
	$request->setData("pass",$pass);
	$request->send();
	$cookies = $conversation->getCookies();

	$conversation2 = new Core_Request_Conversation();
	$request2 = $conversation2->newPost("http://www.cartosort.com");
	$request2->setCookies($cookies);
	$request2->setData("truque", "pécé");
	$request2->send();
	
	return $cookies;
}
?>