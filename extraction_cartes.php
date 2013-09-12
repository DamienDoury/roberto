<?php
function recuperer_combat($cookies, $lien)
{
	$conversation = new Core_Request_Conversation();
	$request = $conversation->newGet("http://www.cartowars.com/" . $lien);
	$request->setCookies($cookies);
	$texte = $request->send();
	
	return $texte;
}

function extraire_cartes($cookies, $lien = "cartes.php")
{
	/*
	Cette fonction prend en paramètre le cookie contenant la session de connexion et un lien vers une page "Combat" ou "Cartes" 
	et retourne un tableau contenant la liste des cartes de l'adversaire (en priorité) sinon du joueur.
	Si le lien vers la page combat n'est pas donné, on récupère la liste des cartes de joueur.
	*/
	
	$texte = recuperer_combat($cookies, $lien);
	
	//Si on est dans une page de combat, on détermine le nombre de cartes de l'adversaire.
	if(preg_match("#<span id=\"ncatt\">([0-9]+) ligne#", $texte, $nombre_lignes)) $nombre_cartes_joueur = $nombre_lignes[1] + 1;
	else $nombre_cartes_joueur = 0;

	//Suppression de la fin du document qui contient les cartes non-équipées.
	$texte = preg_replace("#\t#", "-Tortue42-", $texte);
	$texte = preg_replace("#\r#", "-Renard42-", $texte);
	$texte = preg_replace("#\n#", "-Narval42-", $texte);
	$texte = preg_replace("#Cartes de votre r&eacute;serve :.*$#", "", $texte);
	$texte = preg_replace("#-Tortue42-#", "\t", $texte);
	$texte = preg_replace("#-Renard42-#", "\r", $texte);
	$texte = preg_replace("#-Narval42-#", "\n", $texte);

	//On efface les "span" car les bonus (+) sont placés dans ce conteneur.
	$texte = preg_replace("#<span class=\"c.\">#", "", $texte);
	$texte = preg_replace("#</span>#", "", $texte);

	preg_match_all("#Attaque : (.*)<#", $texte, $liste_attaque);
	preg_match_all("#Vie : (.*)<#", $texte, $liste_vie);
	
	//On supprime les espaces blancs car certaines informations se trouvent sur plusieurs lignes et la fonction preg_replace n'effectue sa recherche que sur une meme ligne.
	$texte = preg_replace("#\t#", "", $texte);
	$texte = preg_replace("#\r#", "", $texte);
	$texte = preg_replace("#\n#", "", $texte);
	
	if(!preg_match("#><a href=\"http://www\.cartowars\.com/compte\.php\">([^<>]+)</a>#", $texte, $pseudo))
	preg_match("#<td height=\"90\" valign=\"middle\" align=\"center\" class=\"vcmbt_infos\">([^<>]+)<br/>#", $texte, $pseudo);

	preg_match_all("#<div class=\"cDef\"><div>([^<>]+)</div><div>([^<>]+)</div><div>([^<>]+)</div>#", $texte, $liste_defenses);
	preg_match_all("#carteP P([EAT])\"#", $texte, $liste_elements);
	
	$cartes[0]['pseudo'] = $pseudo[1];
	
	//Si aucune carte "Combattante" n'est trouvée, on retourne une carte nulle (vide).
	if(sizeof($liste_vie[1]) - $nombre_cartes_joueur <= 0)
	{
		$cartes[0]['element'] = "T";
		$cartes[0]['vie'] = 0;
		$cartes[0]['attaque'] = 0;
		$cartes[0]['defenses']['T'] = 0;
		$cartes[0]['defenses']['A'] = 0;
		$cartes[0]['defenses']['E'] = 0;
		//On entre les données dans la base de données.
		return $cartes;
	}
	
	//Ici on créé un tableau unique et plus explicite contenant la liste des cartes. On effectue les opérations sur les valeurs (ex : 18 + 2 = 20).
	for($i = $nombre_cartes_joueur; $i < sizeof($liste_vie[1]); $i++)
	{
		$cartes[$i - $nombre_cartes_joueur]['element'] = $liste_elements[1][$i];

		if(preg_match("#[+-]#", $liste_vie[1][$i]))
		{
			$valeur = preg_split("#[\+-]#", $liste_vie[1][$i]);
			if(preg_match("#-#", $liste_vie[1][$i])) $cartes[$i - $nombre_cartes_joueur]['vie'] = $valeur[0] - $valeur[1];
			else $cartes[$i - $nombre_cartes_joueur]['vie'] = $valeur[0] + $valeur[1];
		}
		else $cartes[$i - $nombre_cartes_joueur]['vie'] = $liste_vie[1][$i];
		
		if(preg_match("#[+-]#", $liste_attaque[1][$i]))
		{
			$valeur = preg_split("#[\+-]#", $liste_attaque[1][$i]);
			if(preg_match("#-#", $liste_attaque[1][$i])) $cartes[$i - $nombre_cartes_joueur]['attaque'] = $valeur[0] - $valeur[1];
			else $cartes[$i - $nombre_cartes_joueur]['attaque'] = $valeur[0] + $valeur[1];
		}
		else $cartes[$i - $nombre_cartes_joueur]['attaque'] = $liste_attaque[1][$i];
		
		if(preg_match("#[+-]#", $liste_defenses[1][$i]))
		{
			$valeur = preg_split("#[\+-]#", $liste_defenses[1][$i]);
			if(preg_match("#-#", $liste_defenses[1][$i])) $cartes[$i - $nombre_cartes_joueur]['defenses']['T'] = $valeur[0] - $valeur[1];
			else $cartes[$i - $nombre_cartes_joueur]['defenses']['T'] = $valeur[0] + $valeur[1];
		}
		else $cartes[$i - $nombre_cartes_joueur]['defenses']['T'] = $liste_defenses[1][$i];
		
		if(preg_match("#[+-]#", $liste_defenses[2][$i]))
		{
			$valeur = preg_split("#[\+-]#", $liste_defenses[2][$i]);
			if(preg_match("#-#", $liste_defenses[2][$i])) $cartes[$i - $nombre_cartes_joueur]['defenses']['A'] = $valeur[0] - $valeur[1];
			else $cartes[$i - $nombre_cartes_joueur]['defenses']['A'] = $valeur[0] + $valeur[1];
		}
		else $cartes[$i - $nombre_cartes_joueur]['defenses']['A'] = $liste_defenses[2][$i];
		
		if(preg_match("#[+-]#", $liste_defenses[3][$i]))
		{
			$valeur = preg_split("#[\+-]#", $liste_defenses[3][$i]);
			if(preg_match("#-#", $liste_defenses[3][$i])) $cartes[$i - $nombre_cartes_joueur]['defenses']['E'] = $valeur[0] - $valeur[1];
			else $cartes[$i - $nombre_cartes_joueur]['defenses']['E'] = $valeur[0] + $valeur[1];
		}
		else $cartes[$i - $nombre_cartes_joueur]['defenses']['E'] = $liste_defenses[3][$i];
	}
	
	//On entre les données dans la base de données.
	return $cartes;
}
?>