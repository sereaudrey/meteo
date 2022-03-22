<?php
/*
	Ce fichier permet de modifier les données d'une sonde dans la base de données.
    Les informations relatives à la sonde à modifier sont envoyées via un formaulaire POST
	Pour appeler ce fichier via un site web, on utilise les syntaxes suivantes :
		/api-station/v1/update_sensor.php
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

            if (!empty($_POST['sensor'])){
                $id_sensor = intval($_POST['sensor']);
                updateSensor($id_sensor);
            }
            //Si l'id de la sonde à mettre à jour n'est pas précisé
            else{
                // Requête invalide
			    header("HTTP/1.0 405 Method Not Allowed");
            }
			break;

		default:
			// Requête invalide
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

//*******************************************************************
//FONCTION   : updateSensor
//OBJECTIF   : Met à jour les informations d'une sonde spécifique. Le
//	numéro de la sonde à modifier en envoyer via le formulaire contenant
//	les nouvelles informations de la sonde.
//PARAMETRES :
//  + id_sensor : L'id de la sonde à mettre à jour dans la BDD
//*******************************************************************

	function updateSensor($id_sensor){
		//Définit la connexion à utiliser
		global $conn;

		//Récupère les informations pour mettre à jour la sonde
		$name = $_POST["name"];
		$place = $_POST["place"];
		$city = $_POST["city"];
		$country = $_POST["country"];
		$owner = $_POST["owner"];

		//Met en place la requête que nous allons exécuter
		$query = "UPDATE SENSORS SET ";
		$query .= " name_sensor = '" .$name . "', ";
		$query .= " place_sensor = '" .$place . "', ";
		$query .= " city_sensor = '" .$city . "', ";
		$query .= " country_sensor = '" .$country . "', ";
		$query .= " owner_sensor = '" .$owner . "' ";
		$query .= " WHERE id_sensor = " . $id_sensor;

		//Exécute la requête et renvoie si elle a réussie ou échouée
		if (mysqli_query($conn, $query)){
			$response = array('status' => 1, 'status_message' => 'Sonde modifiée avec succes');
		} else{
			$response = array('status' => 0, 'status_message' => 'ERREUR! '.mysqli_error($conn)); 
		}

		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
		//Affiche les résultats au format JSON
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
?>