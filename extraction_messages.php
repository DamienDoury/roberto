<?php
function recuperer_messages($cookies)
{
	$conversation = new Core_Request_Conversation();
	$request = $conversation->newGet("http://www.cartowars.com/messagerie.php");
	$request->setCookies($cookies);
	$texte = $request->send();
	
	return $texte;
}

function extraire_messages($cookies)
{
	
	/*$nom_fichier = "src_mess.txt";
	$fichier = fopen($nom_fichier, "r");
	$texte = fread($fichier, filesize($nom_fichier));
	if($fichier) fclose($fichier);
	else echo "Fichier " . $nom_fichier . " introuvable.<br />";*/
	
	$texte = recuperer_messages($cookies);
	
	$texte = preg_replace("#\t#", "", $texte);
	$texte = preg_replace("#\r#", "", $texte);
	$texte = preg_replace("#\n#", "", $texte);
	
	preg_match_all("#([^>]+)&nbsp;&nbsp#", $texte, $liste_pseudos);
	preg_match_all("#<blockquote>(.+)</blockquote>#U", $texte, $liste_messages);
	
	if(sizeof($liste_pseudos[1]) <= 0) return false;
	
	for($i = 0; $i < sizeof($liste_pseudos[1]); $i++)
	{
		$messages[$i]['pseudo'] = $liste_pseudos[1][$i];
		$messages[$i]['message'] = $liste_messages[1][$i];
	}
	
	return $messages;
}
?>