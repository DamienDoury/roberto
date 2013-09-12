<?php

function push_joueur($id_cartowars, $cartes)
{
	/*
	Cette fonction prend en param�tre l'id de l'adversaire ainsi que son paquet de carte et 
	l'enregistre dans la base de donn�es.
	Ne retourne rien.
	*/
	
	//Connexion avec la bdd.
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=domergo', ********, ********);
	}
	catch (Exception $e)
	{
		die("Voici l'erreur : " . $e->getMessage());
	}
	//Fin connexion avec la bdd.
	
	/*
	Le champ id est la cl� primaire,
	Le champ id_cartowars est unique,
	Le champ date se met � jour � chazque modification.
	*/
	
	$tete = $bdd->query("
	SELECT id_cartowars
	FROM cartowars
	WHERE id_cartowars = " . $id_cartowars . ";
	");
	
	if($tete->fetch())
	{
		//Ici, le joueur existe d�j�.
	}
	else
	{
		$bdd->exec("
		INSERT INTO cartowars (
		id_cartowars, 
		pseudo
		)
		VALUES (
		'" . addslashes($id_cartowars) . "', 
		'" . addslashes($cartes[0]['pseudo']) . "'
		);
		");
	}
		
	for($i = 0; $i < sizeof($cartes); $i++)
	{
		$bdd->exec("
		UPDATE cartowars SET 
		carte" . $i . "_element  = '" . addslashes($cartes[$i]['element']) . "', 
		carte" . $i . "_vie      = " . addslashes($cartes[$i]['vie']) . ", 
		carte" . $i . "_attaque  = " . addslashes($cartes[$i]['attaque']) . ", 
		carte" . $i . "_defenseT = " . addslashes($cartes[$i]['defenses']['T']) . ", 
		carte" . $i . "_defenseA = " . addslashes($cartes[$i]['defenses']['A']) . ", 
		carte" . $i . "_defenseE = " . addslashes($cartes[$i]['defenses']['E']) . " 
		WHERE id_cartowars='" . $id_cartowars . "';
		");
	}
	
	while($i < 10)
	{
		$bdd->exec("
		UPDATE cartowars SET 
		carte" . $i . "_element  = 'T', 
		carte" . $i . "_vie      = 0,
		carte" . $i . "_attaque  = 0,
		carte" . $i . "_defenseT = 0,
		carte" . $i . "_defenseA = 0,
		carte" . $i . "_defenseE = 0
		WHERE id_cartowars='" . $id_cartowars . "';
		");
		
		$i++;
	}
}

function pop_joueur($id_cartowars)
{
	/*
	Cette fonction prend en param�tre l'id du joueur � extraire.
	Elle retourne son paquet de cartes sinon false s'il n'est pas trouv�.
	La premi�re carte contient une variable "date" qui contient la date de la derni�re rencontre.
	Rappel : si un joueur ne poss�de aucune carte, par d�faut on lui attribut une carte nulle (vide).
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
	
	$requete = $bdd->query("
	SELECT *
	FROM cartowars
	WHERE id_cartowars = " . $id_cartowars . ";
	");
	
	//A finir ...
	if($resultat = $requete->fetch())
	{
		$cartes[0]['pseudo'] = $resultat['pseudo'];
		$cartes[0]['date'] = $resultat['date'];
		
		$i = 0;
		do
		{
			if($resultat['carte' . $i . '_vie'] <= 0 && $i > 0) break;
			
			$cartes[$i]['element'] = $resultat['carte' . $i . '_element'];
			$cartes[$i]['vie'] = $resultat['carte' . $i . '_vie'];
			$cartes[$i]['attaque'] = $resultat['carte' . $i . '_attaque'];
			$cartes[$i]['defenses']['T'] = $resultat['carte' . $i . '_defenseT'];
			$cartes[$i]['defenses']['A'] = $resultat['carte' . $i . '_defenseA'];
			$cartes[$i]['defenses']['E'] = $resultat['carte' . $i . '_defenseE'];
			
			$i++;
		}
		while($i < 10);
		
		return $cartes;
	}
	else
	{
		//Ici le joueur n'est pas trouv�.
		return false;
	}
}
?>