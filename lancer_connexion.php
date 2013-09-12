<?php
include "connexion_cartosort.php";
connexion_cartosort("Tropfortman", "cartosorte");
connexion_cartosort("Vivelachance", "vivalachancia");

include "connexion_cartowars.php";
$compte_Tropfortman = connexion_cartowars("Tropfortman", "cartosorte");
$compte_bbbbbb = connexion_cartowars("bbbbbb", "aaaaaa");
?>