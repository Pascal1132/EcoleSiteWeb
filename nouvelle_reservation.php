<?php // Connexion à la base de données
try {
	$bdd = new PDO('mysql:host=localhost;dbname=gestion_Hotel;charset=utf8', 'root', '');
	$dateArrivee = date_create(htmlspecialchars($_POST['dateArrivee']));
	$dateDepart = date_create(htmlspecialchars($_POST['dateDepart']));
	$chambre = htmlspecialchars($_POST['chambre']);
	$utilisateur = htmlspecialchars($_POST['utilisateur']);
	$erreur = "";

	// Vérification de la date
	if(date_diff($dateDepart,$dateArrivee)->format("%R%a")<=0){
		// Vérification du no. de chambre
		$requeteChambre = $bdd-> prepare('SELECT numeroChambre FROM chambres WHERE numeroChambre = '.$chambre);
		$requeteChambre->execute();
		$resultat = $requeteChambre->fetch();
		if($resultat[0]>0){
			// Vérification de l'utilisateur
			$requeteUtilisateur = $bdd-> prepare('SELECT numeroUtilisateur FROM utilisateurs WHERE numeroUtilisateur = '.$utilisateur);
			$requeteUtilisateur->execute();
			$resultat = $requeteUtilisateur->fetch();
			if($resultat[0]>0){

				//Insertion de la réservation grâce à une requête préparée
				try {
					$reqInsertion = $bdd->prepare('INSERT INTO reservations (dateArrivee, dateDepart, numeroChambre_fk, numeroUtilisateur_fk) VALUES(?, ?, ?, ?)');
					$reqInsertion->execute(array($dateArrivee->format('Y-m-d H:i:s'),$dateDepart->format('Y-m-d H:i:s'),$chambre,$utilisateur));

				} catch (Exception $e) {
					die('Erreur : ' . $e->getMessage());
				}
				
			}else{
				$erreur .= "L'utilisateur n'est pas valide.";
			}
		}else{
			$erreur .= "Le numéro de chambre n'est pas valide.";
		}

	}else{
		
		$erreur .= "Le jour d'arrivé doit être inférieur au jour de départ.";
	}
	

	// Redirection du visiteur vers la page d'accueil
	header('Location: index.php?erreur='.urlencode($erreur));
} catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}

?>