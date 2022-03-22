<?php
/*
	Ce fichier permet d'ajouter une sonde dans la base de données.
    Les informations relatives à la sonde à créer sont envoyées via un formaulaire POST
	Pour appeler ce fichier via un site web, on utilise les syntaxes suivantes :
		+ si l'utilisateur souhaite récupérer tous les relevés d'une sonde :
			/api-station/v1/add-sensor
	Cette méthode renvoie un code, 1 si succès, 0 si erreur
*/
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
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
			http_response_code(405);
        		echo json_encode(["message" => "la méthode n'est pas autorisée"]);
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
			http_response_code(200);
        		echo json_encode(["message" => "La requête a été effectué"]);
		} else{
			http_response_code(400);
                        echo json_encode(["message" => "La requête n'a pas effectué".mysqli_error($conn)]); 
		}

		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
		
	}
?>
