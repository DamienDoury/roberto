<?php

function combattre($joueur, $adversaire)
{
	/*
	Cette fonction prend en paramètre 2 paquets de cartes retournés par la fonction "extraire_cartes".
	Elle retourne true si le joueur est capable de vaincre son adversaire.
	*/
	
	$numero_carte_joueur = 0;
	$numero_carte_adversaire = 0;
	$joueur[$numero_carte_joueur];
	$adversaire[$numero_carte_adversaire];
	
	//Boucle de combat.
	while(1)
	{
		//Si les 2 cartes ne peuvent pas se faire de dégâts, on passe à la suivante pour les 2 joueurs.
		if($joueur[$numero_carte_joueur]['attaque'] <= $adversaire[$numero_carte_adversaire]['defenses'][$joueur[$numero_carte_joueur]['element']] 
		&& $adversaire[$numero_carte_adversaire]['attaque'] <= $joueur[$numero_carte_joueur]['defenses'][$adversaire[$numero_carte_adversaire]['element']])
		{
			$numero_carte_joueur++;
			$numero_carte_adversaire++;
			if($numero_carte_joueur >= sizeof($joueur) 
			|| $numero_carte_adversaire >= sizeof($adversaire)) break;
		}

		//Calcul des dégâts ...
		if($joueur[$numero_carte_joueur]['defenses'][$adversaire[$numero_carte_adversaire]['element']] < $adversaire[$numero_carte_adversaire]['attaque']) 
		$joueur[$numero_carte_joueur]['vie'] -= $adversaire[$numero_carte_adversaire]['attaque'] - $joueur[$numero_carte_joueur]['defenses'][$adversaire[$numero_carte_adversaire]['element']];
		
		if($adversaire[$numero_carte_adversaire]['defenses'][$joueur[$numero_carte_joueur]['element']] < $joueur[$numero_carte_joueur]['attaque'])
		$adversaire[$numero_carte_adversaire]['vie'] -= $joueur[$numero_carte_joueur]['attaque'] - $adversaire[$numero_carte_adversaire]['defenses'][$joueur[$numero_carte_joueur]['element']];

		//On passe à la carte suivante.
		if($joueur[$numero_carte_joueur]['vie'] <= 0) $numero_carte_joueur++;
		if($adversaire[$numero_carte_adversaire]['vie'] <= 0) $numero_carte_adversaire++;

		//Si un joueur n'a plus de carte, on quitte le combat.
		if($numero_carte_joueur >= sizeof($joueur)
		|| $numero_carte_adversaire >= sizeof($adversaire)) break;
	}
	
	//Si à la fin du combat il reste des cartes au joueur, on le déclare vainqueur.
	if($numero_carte_joueur >= sizeof($joueur)) return 0;
	else return 1;
}

?>