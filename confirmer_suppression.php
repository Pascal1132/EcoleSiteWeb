<?php 
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=gestion_Hotel;charset=utf8', 'root', '');
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	$identifiant = $_GET['reservation_id'];
	//Récupérer la réservation avec une requête préparée
	$requeteReservation = $bdd-> prepare('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk WHERE numeroReservation = '.$identifiant);
	$requeteReservation->execute();
	$resultat = $requeteReservation->fetch();
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Confirmer la suppression</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		#boutonOUI{
			display: block;
			background-color: green;
		}
		#boutonOUI:hover{
			background-color: #326771;
		}
		#boutonOUI:active{
			background-color: #28464B;
		}
	</style>
</head>
<body>
	<div class="partie">
	<?php if($resultat[0]>0){
		?>
		<h2>Êtes-vous certain de vouloir supprimer cette réservation?</h2>
		<fieldset >
			<legend style="text-align: center;">Informations</legend>
			<p >Date d'arrivée : <?=$resultat['dateArrivee']?> <br>
		Date de départ : <?=$resultat['dateDepart']?> <br>
		Nom d'utilisateur : <?=$resultat['nomUtilisateur']?> <br>
		Numéro de la chambre : <?=$resultat['numeroChambre_fk']?></p>
		</fieldset>
		<br>
		<a id="boutonOUI" href="supprimer_reservation.php?reservation_id=<?=$resultat['numeroReservation']?>">OUI</a><br><a style="display: block;" href="index.php?erreur=Annulation%20de%20la%20suppression%20de%20l%27enregistrement.">NON</a>
		<?php
	}else{
		echo "<p>Numéro de réservation non valide.</p>";
	}
	?>
	
		

	</div>
</body>
</html>