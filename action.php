<?php
/*
Roberto - version 1.0 - 01/01/2011
ROBerto saBOTe cartowars !

Ce script est le script principal du fonctionnement du bot, il regroupe les diff�rentes fonctions et les coordonne.
Voici ses principales actions :
- il connecte le compte pour obtenir la carte du jour,
- il effectue quelques combats pour faire monter le joueur dans le classement,
- il relaye les messages re�us sur Cartowars.

Ce script n'est pas optimis� au niveau de la rapidit� d'ex�cution.

Edit : modifi�e le 03/03/2011 pour r�vision de la condition de autorisation_quotidienne.
*/

require_once "autorisation_combat.php";
require_once "bdd.php";
require_once "combattre.php";
require_once "connexion_cartowars.php";
require_once "extraction_cartes.php";
require_once "extraction_combats.php";
require_once "extraction_messages.php";
require_once "lancer_combat.php";
require_once "mail_cartowars.php";
require_once "recuperer_adversaires.php";

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

$liste_comptes = $bdd->query("
SELECT *, HOUR(derniere_action) AS 'heure', MINUTE(derniere_action) AS 'minute', SECOND(derniere_action) AS 'seconde', MONTH(derniere_action) AS 'mois', DAY(derniere_action) AS 'jour', YEAR(derniere_action) AS 'annee'
FROM comptes_cartowars;
");

function autorisation_quotidienne($compte, $bdd)
{
	if(time() - mktime($compte['heure'], $compte['minute'], $compte['seconde'], $compte['mois'], $compte['jour'], $compte['annee']) > 60*60*24)
	{
		$bdd->query("
		UPDATE comptes_cartowars SET 
		derniere_action = NOW()
		WHERE id = '" . $compte['id'] . "';
		");
		
		return true;
	}
	else return false;
}

$nombre_combats_voulus = 9;

while($compte = $liste_comptes->fetch())
{
	//echo "Test connexion pour " . $compte['pseudo'] . " ...<br />";
	if(!autorisation_quotidienne($compte, $bdd)) continue; //Le script doit s'ex�cuter une seule fois par jour car on obtient une carte par jour et on ne peut battre un meme adversaire qu'une fois par jour.
	//echo "Connexion r&eacute;ussie !<br /><br />";
	$session = connexion_cartowars($compte['pseudo'], $compte['mdp']);
	$nombre_combats_effectues = 0;
	$nombre_recherches_adversaires = 0;

	do
	{
		$adversaires = recuperer_adversaires($session);
		$nombre_recherches_adversaires++;
		$nombre_combats_rates = 0;

		for($i = 0; $i < sizeof($adversaires); $i++)
		{
			$paquet_joueur = extraire_cartes($session); //On r�cup�re la liste de nos cartes � chaque boucle au cas o� elle aurait chang�e (future fonction).
			if(!autorisation_combat($adversaires[$i]['id'], $paquet_joueur)) continue;
			lancer_combat($session, $adversaires[$i]['id']); //Ici il faut effectuer une condition avant de lancer le combat.
			sleep(1); //On met le programme en pause, car sinon le serveur distant n'a pas le temps d'actualiser notre historique.
			$dernier_combat = extraction_dernier_combat_attaquant($session);

			if(strtolower($dernier_combat['pseudo']) == strtolower($adversaires[$i]['pseudo']))
			{
				$paquet_adversaire = extraire_cartes($session, $dernier_combat['lien_combat']);
				push_joueur($adversaires[$i]['id'], $paquet_adversaire);
				$nombre_combats_effectues++;
			}
			else
			{
				//La liste des adversaires r�cup�r�e par la fonction "recuperer_adversaires" est int�gralement affrontable.
				//Si un combat ne peut avoir lieu, c'est forc�ment qu'on a chang� de niveau.
				//Apr�s avoir constat� 3 �checs cons�cutifs, on est s�r de cette hypoth�se et donc on r�cup�re la nouvelle liste d'adversaires.
				$nombre_combats_rates++;
			}
			
			if($nombre_combats_rates > 3 || $nombre_combats_effectues >= $nombre_combats_voulus) break;
			
			//sleep(mt_rand(2,5));
		}
	}
	while($nombre_combats_effectues < $nombre_combats_voulus && $nombre_recherches_adversaires < 5);

	$messages_recup = extraire_messages($session);
	if($messages_recup) envoi_mail($compte['pseudo'], $compte['mail'], $messages_recup);
}
?>