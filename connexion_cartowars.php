<?php
require_once "WebConversation/Abstract.php";
require_once "WebConversation/Post.php";
require_once "WebConversation/Get.php";
require_once "WebConversation/Conversation.php";
	
function connexion_cartowars($pseudo, $pass)
{
	$_SERVER["REQUEST_URI"] = "http://www.cartowars.com/login/login.php";
	$_SERVER["SCRIPT_NAME"] = "http://www.cartowars.com/login/login.php";

	$conversation = new Core_Request_Conversation();
	$request = $conversation->newPost("http://www.cartowars.com/login/login.php");
	$request->setData("pseudo", $pseudo);
	$request->setData("pass",$pass);
	$request->send();
	$cookies = $conversation->getCookies();

	$conversation2 = new Core_Request_Conversation();
	$request2 = $conversation2->newPost("http://www.cartowars.com");
	$request2->setCookies($cookies);
	$request2->setData("truque", "pécé");
	$request2->send();
	
	return $cookies;
}
?>