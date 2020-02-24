<?php
	//Affichage des erreurs
$erreur = "ERREUR:";
if(isset($_GET['erreur'])){
	$erreur = htmlspecialchars(urldecode(($_GET['erreur'])));
	echo "<h3 style=\"color:red;\">".$erreur."</h3>";

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

	//Récupérer les numéros des chambres et les types de chambres
$chambresRequete = $bdd->query('SELECT * FROM chambres ORDER BY numeroChambre ');
$optionsChambres = "";

while($ligne = $chambresRequete->fetch()){
	$optionsChambres .= "<option value='".$ligne["numeroChambre"]."'>".$ligne["numeroChambre"]."   (".$ligne["typeChambre_fk"].")</option>";
	
}
	//Récupérer les noms et les id des utilisateurs
$utilisateursRequete = $bdd->query('SELECT numeroUtilisateur, nomUtilisateur FROM utilisateurs ORDER BY numeroUtilisateur');
$optionsUtilisateurs = "";
while($ligne = $utilisateursRequete->fetch()){
	$optionsUtilisateurs .= "<option value='".$ligne[0]."'>".$ligne[1]."</option>";
}

	//Récupérer les enregistrements de la table reservations
$reservationsRequete = $bdd->query('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre ORDER BY numeroReservation DESC LIMIT 0, 10');
$type = $bdd->query('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre ORDER BY numeroReservation DESC LIMIT 0, 10');;

$chambreType = array();
while ($donnee = $type->fetch()) {
	array_push($chambreType, $donnee);
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
<script type="text/javascript">
	function afficherChambre(id) {
		<?php 
			$reservationsRequete;
			echo "var reservationsRequete =".json_encode($chambreType).";\n";
		 ?>
		 

		 alert(reservationsRequete[id][0]);
		 
	}
	function masquerChambre(id) {
		<?php 
			$reservationsRequete;
			echo "var reservationsRequete =".json_encode($chambreType).";\n";
		 ?>
		 
	}
	function dump(obj) {
 var out = '';
 for (var i in obj) {
 out += i + ": " + obj[i] + "\n";
 }
alert(out);
}
</script>

<body>
	<form action="nouvelle_reservation.php" method="post" class="formulaire" id="formulaireReservation">
		<h2>Nouvelle réservation</h2>
		<fieldset>
			<legend>Dates de réservation</legend>
			<label for="dateArrivee" class="align-left">Arrivée : </label>
			<input type="date" name="dateArrivee" id="dateArrivee" required class="align-right" />
			<label for="dateDepart" class="align-left">Départ : </label>
			<input type="date" name="dateDepart" id="dateDepart" required class="align-right" />
		</fieldset>
		<br>
		<fieldset>
			<legend>Informations</legend>

			<ul class="field-style">

				<li><label for="numeroChambre" class="align-left">Numéro de la chambre</label>
					<select name="chambre" form="formulaireReservation" class="align-right select-style">
					<option value="" selected disabled hidden>Choisir</option>
				<?=$optionsChambres?>
				</select></li>
				<li><label for="villeUtilisateur" class="align-left">Nom de l'utilisateur</label>
					<select name="utilisateur" form="formulaireReservation" class="align-right select-style">
					<option value="" selected disabled hidden>Choisir</option>
				<?=$optionsUtilisateurs?>
				</select></li>
			</ul>
		</fieldset>



		<br>
		<input type="submit" value="Ajouter" />
		<input type="reset" name="Reinitialiser"/>
		<br>
	</p>
</form>
<div class="partie">
<h2>Liste des enregistrements dans la table :</h2>
<table class="listeEnregistrement" align="center">
	<tr >
		<th>Modifier</th>
		<th>Supprimer</th>
		<th>Date d'arrivée</th>
		<th>Date de départ</th>
		<th>No. chambre</th>
		<th>Nom de l'utilisateur</th>
	</tr>
	<?php 
		while ($reservations = $reservationsRequete->fetch())
			{
				echo "<tr><td>".'<a href="modifier_reservation.php?id=' . htmlspecialchars($reservations['numeroReservation']) . '">[modifier]</a>' ."</td>" .
						 "<td>".'<a href="confirmer_suppression.php?reservation_id=' . htmlspecialchars($reservations['numeroReservation']) . '">[supprimer]</a>' ."</td>" .
						 "<td>" . htmlspecialchars($reservations['dateArrivee']) . "</td>".
						 "<td>" . htmlspecialchars($reservations['dateDepart']) . "</td>".
						 "<td onmouseover=\"afficherChambre('".$reservations['numeroReservation']."');this.style.backgroundColor='#006db9'\" onmouseout=\"masquerChambre();this.style.backgroundColor=''\">" . htmlspecialchars($reservations['numeroChambre_fk']) . "<i> (".htmlspecialchars($reservations['typeChambre_fk']).")</i></td>".
						 "<td>".htmlspecialchars($reservations['nomUtilisateur'])."</td>
					 </tr>";
			}
	?>
</table>
</div>



</div>
<p><a href="apropos.html" class="bouton">À propos</a></p>
</body>
</html>

