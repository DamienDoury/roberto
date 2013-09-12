<?php
include "extraction_cartes.php";
include "combattre.php";

$fichier = fopen("mes_cartes.txt", "r");
$texte = fread($fichier, filesize("mes_cartes.txt"));
if($fichier != NULL) fclose($fichier);
$cartes_joueur = extraire_cartes($texte);

$fichier2 = fopen("mon_combat.txt", "r");
$texte2 = fread($fichier2, filesize("mon_combat.txt"));
if($fichier2 != NULL) fclose($fichier2);
$cartes_adversaire = extraire_cartes($texte2);

echo "<pre>";
print_r($cartes_joueur);
print_r($cartes_adversaire);
echo "</pre><br />";

include "bdd.php";
push_joueur(5928, $cartes_adversaire);

$res = combattre($cartes_joueur, $cartes_adversaire);
echo "Res : " . $res;

/*
1) On se connecte avec la fonction qui retourne les cookies.
2) On récupère la liste des adversaires.
3) On récupère le dernier combat.
4) BOUCLE :
	- On lance un combat.
	- On récupère le dernier combat (défense) de notre historique.
	- ...
*/
?>