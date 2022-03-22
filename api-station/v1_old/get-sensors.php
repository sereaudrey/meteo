<?php
/*
	Ce fichier permet de récupérer toutes les informations des sondes enregistrées
	Pour appeler ce fichier via un site web, on utilise les syntaxes suivantes :
		+ si l'utilisateur souhaite récupérer toutes les sondes :
			/api-station/v1/get-sensors.php
		+ si l'utilisateur souhaite récupérer les informations d'une sonde particulière :
			/api-station/v1/get-sensors.php?sensor={id_sensor}
	Les données récupérées sont renvoyées au format JSON
*/

	//Définit les variables pour la connexion
	$server = "localhost";
	$username = "root";
	$passwd = "stationmeteo";
	$db = "stationmeteo";
	//Connecte l'API à la Base de données
	$conn = mysqli_connect($server, $username, $passwd, $db);

	//Affiche une erreur si la connexion ne s'effectue pas
	if (mysqli_connect_errno()){
		echo "Impossible de se connecter à MySQL : ".mysqli_connect_errno();
		exit();
	}

	//Récupère la méthode appelé lors de l'appel à l'API (GET, POST ou PUT)
	$request_method = $_SERVER["REQUEST_METHOD"];
	//En fonction du verbe HTTP utilisé
	switch ($request_method) {

		case 'GET':
            //S'il veut récupérer les informations d'une seule sonde
            if(!empty($_GET["sensor"])){
                $sensor = intval($_GET["sensor"]);
                getSensors($sensor);
            } 
            //S'il veut récupérer les informations de toutes les sondes
            else{
                getSensors();
            }
			break;

		default:
			// Requête invalide
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

//*******************************************************************
//FONCTION   : gestSensors
//OBJECTIF   : Récupère les données relatives à toutes les sondes ou
//	à une sonde en particulier.
//PARAMETRES : 
//	id_sensor : Le numéro de la sonde pour laquelle il faut récupérer
// 		les données. Si les données de toutes les sondes doivent être
//		être récupérées, il ne faut pas préciser de paramètres.
//*******************************************************************

	function getSensors($id_sensor = 0){
		//Définit la connexion à utiliser
		global $conn;
		//Met en place la requête que nous allons exécuter
		$query = "SELECT * FROM SENSORS";
		if ($id_sensor != 0) {
			$query .= " WHERE id_sensor = ".$id_sensor;
		}

		$reponse = array();
		//Exécute la requête et récupère le résultat
		$result = mysqli_query($conn, $query);
		//Ajoute les réponses dans un tableau
		while($row = mysqli_fetch_array($result)){
			$reponse[] = $row;
		}
		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
		//Affiche les résultats au format JSON
		echo json_encode($reponse, JSON_PRETTY_PRINT);
	}
?>