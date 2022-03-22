<?php
/*
	Ce fichier permet d'ajouter une sonde dans la base de données.
    Les informations relatives à la sonde à créer sont envoyées via un formaulaire POST
	Pour appeler ce fichier via un site web, on utilise les syntaxes suivantes :
		+ si l'utilisateur souhaite récupérer tous les relevés d'une sonde :
			/api-station/v1/add-sensor
	Cette méthode renvoie un code, 1 si succès, 0 si erreur
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

		case 'POST':
            //On ajoute une sonde dans la base de données
            AddSensor();
            break;

		default:
			// Requête invalide
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

//*******************************************************************
//FONCTION   : AddSensor
//OBJECTIF   : Ajoute une sonde dans la base de données suite à l'envoi
//	d'un formulaire contenant les données de cette sonde
//*******************************************************************

	function AddSensor(){
		//Définit la connexion à utiliser
		global $conn;
		//Récupère les informations de la sonde à créer
		$name = $_POST["name"];
		$place = $_POST["place"];
		$city = $_POST["city"];
		$country = $_POST["country"];
		$owner = $_POST["owner"];
		$first_day = date('Y-m-d');

		//Met en place la requête que nous allons exécuter
		$query = "INSERT INTO SENSORS(name_sensor, place_sensor, city_sensor, country_sensor, owner_sensor, first_day_sensor, number_releases) ";
		$query .= " VALUES ('".$name."', '".$place."', '".$city."', '".$country."', '".$owner."', '".$first_day."', 0)";
		
        //Exécute la requête et renvoie si elle a réussie ou échouée
		if (mysqli_query($conn, $query)){
			$response = array('status' => 1, 'status_message' => 'Sonde ajoute avec succes');
		} else{
			$response = array('status' => 0, 'status_message' => 'ERREUR! '.mysqli_error($conn)); 
		}

		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
		//Affiche les résultats au format JSON
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
?>