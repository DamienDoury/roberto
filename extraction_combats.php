<?php
require_once "WebConversation/Abstract.php";
require_once "WebConversation/Post.php";
require_once "WebConversation/Get.php";
require_once "WebConversation/Conversation.php";

function recuperer_historique_combats($cookies)
{
	$conversation = new Core_Request_Conversation();
	$request = $conversation->newGet("http://www.cartowars.com/voscombats.php");
	$request->setCookies($cookies);
	$texte = $request->send();
	
	return $texte;
}

function extraire_historique_combats($cookies)
{
	$texte = recuperer_historique_combats($cookies);

	$texte = preg_replace("#</a>#", "", $texte);
	
	preg_match_all("#vcombat\.php\?c=([0-9]*)&rv=1#", $texte, $liste_liens);
	preg_match_all("#([^>]+)&nbsp;&nbsp#", $texte, $liste_pseudos);

	$liste_combats['lien_combat'] = $liste_liens[0];
	$liste_combats['pseudo'] = $liste_pseudos[1];

	return $liste_combats;
}

function extraction_dernier_combat_attaquant($cookies)
{
	$texte = recuperer_historique_combats($cookies);

	$texte = preg_replace("#\t#", "", $texte);
	$texte = preg_replace("#\r#", "", $texte);
	$texte = preg_replace("#\n#", "", $texte);
	$texte = preg_replace("#^.+attaquant :#", "", $texte);
	$texte = preg_replace("#</a>#", "", $texte);

	preg_match("#vcombat\.php\?c=([0-9]*)&rv=1#", $texte, $lien);
	preg_match("#([^>]+)&nbsp;&nbsp#", $texte, $pseudo);
	
	$combat['lien_combat'] = $lien[0];
	$combat['pseudo'] = $pseudo[1];
	
	return $combat;
}
?>