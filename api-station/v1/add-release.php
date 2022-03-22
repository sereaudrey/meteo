<?php
/*
	Ce fichier permet d'ajouter un relevé de température et d'humidité dans la base de données.
    Pour utiliser cette méthode, l'utilisateur doit obligatoirement précisé l'id de la sonde qui a
effetué le relevé.
	Pour appeler ce fichier via un site web, on utilise la syntaxe suivante :
		/api-station/v1/add-release.php?sensor={id_sonde}&humidity={humidite}&temperature={temperature}
	Cette méthode renvoie un code, 1 si succès, 0 si erreur
*/
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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
		
		case 'GET':
			
			if (intval($_GET['sensor']) != 0){
               	 		$id_sensor = intval($_GET['sensor']);
               			AddRelease($id_sensor);
				                		
            } else{
                // Requête invalide
                //on envoie un code réponse
		http_response_code(400);
		echo json_encode(["message" => "URI incomplètes"]);
            }

			break;       

		default:
			// Requête invalide
			 http_response_code(405);
                	 echo json_encode(["message" => "la méthode n'est pas autorisée"]);
			break;
	}


//*******************************************************************
//FONCTION   : AddRelease
//OBJECTIF   : Ajoute un relevé dans la base de données suite à l'envoi
//	d'un formulaire contenant les données du relevé.
//PARAMETRES : 
//	id_sensor : Le numéro de la sonde pour laquelle il faut ajouter un
//		relevé. Ce paramètre est obligatoire
//*******************************************************************

	function AddRelease($id_sensor){
		//Définit la connexion à utiliser
		global $conn;
		//Récupère les informations pour créer le relevé
		$humidity = $_GET["humidity"];
		$temperature = $_GET["temperature"];
		$date = date('Y-m-d');
		$time = date("H:i:s");

		//Met en place la requête que nous allons exécuter
		$query = "INSERT INTO RELEASES(id_sensor, date_release, time_release, humidity, temperature) ";
		$query .= " VALUES ('".$id_sensor."', '".$date."', '".$time."', ".$humidity.", ".$temperature.")";

		//Exécute la requête et renvoie si elle a réussie ou échouée
		if (mysqli_query($conn, $query)){
			http_response_code(201);
                        echo json_encode(["message" => "Relevé ajouté avec succès"]);
		} else{
			http_response_code(400);
                        echo json_encode(["message" => "ERREUR envoie échoué".mysqli_error($conn)]);
		}

		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
//		header('HTTP/1.0 200 OK');
		//Affiche les résultats au format JSON
		}
?>
