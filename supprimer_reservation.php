<?php // Connexion à la base de données
try {
	$bdd = new PDO('mysql:host=localhost;dbname=gestion_Hotel;charset=utf8', 'root', '');
	$identifiant = $_GET['reservation_id'];
	
	$requeteReservation = $bdd->query("DELETE FROM `reservations` WHERE `reservations`.`numeroReservation` = ".$identifiant);
	
	// Redirection du visiteur vers la page d'accueil
	header('Location: index.php?erreur='.urlencode($erreur));
} catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}

?>