<?php

function envoi_mail($nom_compte, $destinataire, $messages)
{
//Objet
$objet = "Mail sur Cartowars";

//Message
$message = "
<html>
	<head>
		<title>Mail sur cartowars</title>
		<style>
			body
			{
			text-align: center;
			font-family: arial, verdana, sans-serif;
			}
			
			.conteneur
			{
			margin: auto;
			padding: 15px;
			width: 700px;
			border-style: dashed;
			border-width: 1px;
			background-color: #FBF02F;
			}
			
			.logo
			{
			text-align: center;
			}
			
			p
			{
			color: black;
			text-align: left;
			font-size: 14px;
			}
			
			img
			{
			padding: 40px 0px;
			border: none;
			border-width: 0px;
			}
			
			big
			{
			font-family: Verdana, monospace;
			color: red;
			font-size: 25px;
			}
			
			.texte
			{
			font-family: Verdana, monospace;
			font-size: 18px;
			}
		</style>
	</head>
	
	<body>
		<div class='conteneur'>
			<div class='logo'>
			<a href='http://domergo.kegtux.org' target='_blank'><img src='http://domergo.kegtux.org/images/icones/logo_domergo.png' width='600' alt='Domergo' /></a>
			</div>
			
			<p>
				Vous recevez ce mail car votre compte " . $nom_compte . " a re&ccedil;u " . sizeof($messages) . " nouveau";
				if(sizeof($messages) >= 2) $message .= "x";
				$message .= " message";
				if(sizeof($messages) >= 2) $message .= "s";
				$message .= " :<br /><br />
			</p>
			<p class='texte'>";
				
				for($i = 0; $i < sizeof($messages); $i++)
				{
					$message .= "<center><b>" . $messages[$i]['pseudo'] . "<br /></b></center>";
					$message .= $messages[$i]['message'] . "<br /><br />";
				}
				
				$message .=
			"</p>
			
			<p>
				Pour vous connecter sur cartowars, <a href='http://www.cartowars.com'>cliquez sur ce lien</a>. <br />
				Ne r&eacute;pondez pas directement &agrave; ce mail, sinon personne ne vous recontactera ! <br />
				Si vous souhaitez r&eacute;pondre &agrave; ce mail, contactez l'administrateur &agrave; l'adresse <a href='mailto:domergo@kegtux.org'>domergo@kegtux.org</a> <br />
			</p>
			
		</div>
  </body>
</html>
";

//En-tête pour envoyer un mail en html
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: Roberto <services@kegtux.org>' . "\r\n"; //Apparait dans le résumé du mail.

mail($destinataire, $objet, $message, $headers);
}
?>