<?php
require_once "WebConversation/Abstract.php";
require_once "WebConversation/Post.php";
require_once "WebConversation/Get.php";
require_once "WebConversation/Conversation.php";

function lancer_combat($cookies, $id)
{
	$conversation = new Core_Request_Conversation();
	$request = $conversation->newPost("http://www.cartowars.com/combat.php");
	$request->setCookies($cookies);
	$request->setData("Submit", "Combattre");
	$request->setData("n", $id);
	$request->send();
}
?>