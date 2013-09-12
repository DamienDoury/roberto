<?php
function autorisation_combat($id_adversaire, $cartes_joueur)
{
	/*
	Retourne true si le combat doit etre lancé.
	Si l'adversaire est inconnu on lance le combat.
	S'il est connu, on simule le combat.
	Si on gagne, on retourne true, sinon false.
	*/
	
	/*
	Le système de combat est parfait est difficile à mettre en place.

	Voici les informations dont on dispose :
	- une liste de joueurs connus avec toutes leurs cartes,
	- la liste des joueurs qu'on peut affronter,
	- l'historique des 10 derniers combats d'un joueur (avant de l'affronter).

	Pour les joueurs que l'on connait :
	L'idéal serait de pouvoir adapter notre jeu de cartes en fonction des cartes de l'adversaire (en piochant dans notre réserve).
	Ainsi le bot serait totalement autonome.

	Pour les adversaires jamais rencontrés :
	On pourrait regarder l'historique de ses derniers combats.
	On cherche un joueur qui l'a battu et que l'on connait, et on copie la disposition de ses cartes avant de lancer le combat.
	Ce procédé augmente les chances de victoire.

	Pour les autres :
	On regarde leur pourcentage de victoire dans leur historique avant de lancer le combat.
	*/
	
	//Connexion avec la bdd.
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=domergo', 'domergo', 'bourgeon');
	}
	catch (Exception $e)
	{
		die("Voici l'erreur : " . $e->getMessage());
	}
	//Fin connexion avec la bdd.

	$cartes_adversaire = pop_joueur($id_adversaire);
	
	if($cartes_adversaire)
	{
		return combattre($cartes_joueur, $cartes_adversaire); //Au lieu de retourner false si on perd le combat face à un joueur connu, on peut essayer de modifier nos combinaisons de cartes.
	}
	else
	{
		return true; //Si on ne connait pas un joueur, on pourrait analyser son historique par exemple.
	}
}
?>