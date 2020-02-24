<?php
	//Affichage des erreurs

if(isset($_GET['id'])){
	$id = htmlspecialchars($_GET['id']);
	

}else{
	echo "ERREUR. Aucun identifiant est passé en paramètre";
}


	//Connexion à la BD
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=gestion_Hotel;charset=utf8', 'root', '');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

$reqAfficher=$bdd->prepare('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre WHERE numeroReservation = ?');
$resultat=$reqAfficher->execute(array($id));
$resultat= $reqAfficher->fetch();

	//Récupérer les numéros des chambres et les types de chambres
$chambresRequete = $bdd->query('SELECT * FROM chambres ORDER BY numeroChambre ');
$optionsChambres = "";

while($ligne = $chambresRequete->fetch()){
	$optionsChambres .= "<option value='".$ligne["numeroChambre"]."'>".$ligne["numeroChambre"]."   (".$ligne["typeChambre_fk"].")</option>";
	
}

$utilisateursRequete = $bdd->query('SELECT numeroUtilisateur, nomUtilisateur FROM utilisateurs ORDER BY numeroUtilisateur');
$optionsUtilisateurs = "";
while($ligne = $utilisateursRequete->fetch()){
	$optionsUtilisateurs .= "<option value='".$ligne[0]."'>".$ligne[1]."</option>";
}



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Gestion de l'hôtel</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
</head>


<body>
	
	<form action="miseajour_reservation.php" method="post" class="formulaire" id="formulaireReservation">
		<h2>Modifier une réservation</h2>
		<fieldset>
			<legend>Dates de réservation</legend>
			
			<label for="dateArrivee" class="align-left">Arrivée : </label>
			<input type="date" name="dateArrivee" id="dateArrivee" required class="align-right" value="<?=$resultat['dateArrivee']?>" />
			<label for="dateDepart" class="align-left">Départ : </label>
			<input type="date" name="dateDepart" id="dateDepart" required class="align-right" value="<?=$resultat['dateDepart']?>"/>
		</fieldset>
		<br>
		<fieldset>
			<legend>Informations</legend>

			<ul class="field-style">

				<li><label for="numeroChambre" class="align-left">Numéro de la chambre</label>
					<select name="chambre" form="formulaireReservation" class="align-right select-style" value="<?=$resultat['dateDepart']?>">
					<option value="<?=$resultat['numeroChambre']?>" selected hidden><?=$resultat['numeroChambre']."   (".$resultat['typeChambre_fk'].")"?></option>
				<?=$optionsChambres?>
				</select></li>
				<li><label for="villeUtilisateur" class="align-left">Nom de l'utilisateur</label>
					<select name="utilisateur" form="formulaireReservation" class="align-right select-style">
					<option value="<?=$resultat['numeroUtilisateur']?>" selected hidden><?=$resultat['nomUtilisateur']?></option>
				<?=$optionsUtilisateurs?>
				</select></li>
			</ul>
		</fieldset>



		<br>
		<input type="submit" value="Modifier" />
		<input type="reset" name="Reinitialiser" value="Rétablir les valeurs" />
		<input type="hidden" name="id" value="<?=$id?>">
		<br>
	</p>
</form>




</body>
</html>

