<?php
require_once "WebConversation/Abstract.php";
require_once "WebConversation/Post.php";
require_once "WebConversation/Get.php";
require_once "WebConversation/Conversation.php";
	
function recuperer_adversaires($cookies)
{
	/*
	Prend en paramètre le cookie de la session connectée d'un joueur et 
	retourne la liste des id et des pseudos des adversaires du meme niveau.
	Certains des derniers joueurs sont en double.
	*/
	
	$rang = -10;
	do
	{
		$rang += 10;
		
		//On envoie la requete.
		$conversation = NULL;
		$conversation = new Core_Request_Conversation();
		$request = $conversation->newGet("http://www.cartowars.com/combattre.php?t=" . $rang);
		$request->setCookies($cookies);
		$texte = $request->send();

		preg_match_all("#combattre-([^_]+)_([^\.]+)\.php#", $texte, $adversaires);
		
		//Là on constitue le tableau de la liste des joueurs.
		for($i = 0; $i < 10; $i++)
		{
			$liste_temp_adversaires[$i + $rang]['pseudo'] = $adversaires[1][$i];
			$liste_temp_adversaires[$i + $rang]['id'] = $adversaires[2][$i];
		}
	}
	while(preg_match("#Forward\.gif#", $texte)); //On continue tant qu'on rencontre le bouton pour descendre.
		
	//On inverse l'ordre de la liste pour que les adversaires les moins forts se trouvent en premier.
	$nb = 0;
	for($i = 0; $i < sizeof($liste_temp_adversaires); $i++)
	{
		if($liste_temp_adversaires[sizeof($liste_temp_adversaires) - $i - 1]['pseudo'] != "") //Le pseudo n'est pas récupéré si on ne peut pas affronter l'adversaire.
		{
			$liste_adversaires[$nb]['pseudo'] = $liste_temp_adversaires[sizeof($liste_temp_adversaires) - $i - 1]['pseudo'];
			$liste_adversaires[$nb]['id'] = $liste_temp_adversaires[sizeof($liste_temp_adversaires) - $i - 1]['id'];
			$nb++;
		}
	}
	return $liste_adversaires;
}

/*
Pour voir le résultat :
include "connexion_cartowars.php";
$cookies_Tropfortman = connexion_cartowars("Tropfortman", "cartosorte");
recuperer_adversaires($cookies_Tropfortman);
*/
?>