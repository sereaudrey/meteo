<?php
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
		
		//Si l'utilisateur souhaite récupérer des informations
		case 'GET':
			
			//Si il souhaite récupérer les informations relatives aux sondes
			if ($_GET["object"] == "sensors"){
				//S'il veut récupérer les informations d'une seule sonde
				if(!empty($_GET["sensor"])){
					$sensor = intval($_GET["sensor"]);
					getSensors($sensor);
				} 
				//S'il veut récupérer les informations de toutes les sondes
				else{
					getSensors();
				}
			}

			//S'il souhaite récupérer les informations relatives aux relevés de température
			elseif ($_GET['object'] == "releases") {
				//S'il veut récupérer les informations d'une seule sonde
				if(!empty($_GET["sensor"])){
					$sensor = intval($_GET["sensor"]);
					getReleases($sensor);
				} 
				//S'il veut récupérer les informations de toutes les sondes
				else{
					getReleases();
				}
			}

			break;


		//Dans le cas d'une requête CREATE (POST)
		case 'POST':
			//En focntion de ceque l'utilisateur souhaite ajouter
			switch ($_GET['object']){
				//Si l'enregistrement a ajouté correspond à un capteur
				case 'sensor':
					AddSensor();
				//Si l'enregistrement a ajouté est un relevé
				case 'release':
					if (intval($_POST['id_sensor']) != 0){
						$id_sensor = intval($_POST['id_sensor']);
						AddRelease($id_sensor);
					}
			}
			break;

		//Dans le cas où l'utilisateur souhaite mettre à jour un capteur
		case 'PUT':
			updateSensor();
			break;

		default:
			// Requête invalide
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

//--------------------DEFINITION DES FONCTIONS-----------------------

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


//*******************************************************************
//FONCTION   : getReleases
//OBJECTIF   : Récupère les données relatives aux relevés de toutes les 
//	sondes ou d'une sonde en particulier.
//PARAMETRES : 
//	id_sensor : Le numéro de la sonde pour laquelle il faut récupérer
// 		les relevés. Si les relevés de toutes les sondes doivent être
//		être récupérés, il ne faut pas préciser de paramètres.
//*******************************************************************

	function getReleases($id_sensor = 0){
		//Définit la connexion à utiliser
		global $conn;
		//Met en place la requête que nous allons exécuter
		$query = "SELECT * FROM RELEASES";
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
			$response = array('status' => 1, 'status_message' => 'Relevé ajoute avec succes');
		} else{
			$response = array('status' => 0, 'status_message' => 'ERREUR! '.mysqli_error($conn)); 
		}
		//Envoie un en-tête HTTP
		header('Content-Type: application/json');
		//Affiche les résultats au format JSON
		echo json_encode($response, JSON_PRETTY_PRINT);
	}


//*******************************************************************
//FONCTION   : updateSensor
//OBJECTIF   : Met à jour les informations d'une sonde spécifique. Le
//	numéro de la sonde à modifier en envoyer via le formulaire contenant
//	les nouvelles informations de la sonde.
//*******************************************************************

	function updateSensor(){
		//Définit la connexion à utiliser
		global $conn;
		//Définit un objet PUT qui nous permettra de récupérer les informations du formulaire
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		//Récupère les informations pour mettre à jour la sonde
		$sensor = $_PUT['id_sensor'];
		$name = $_PUT["name"];
		$place = $_PUT["place"];
		$city = $_PUT["city"];
		$country = $_PUT["country"];
		$owner = $_PUT["owner"];
		//Met en place la requête que nous allons exécuter
		$query = "UPDATE SENSORS SET ";
		$query .= " name_sensor = '" .$name . "', ";
		$query .= " place_sensor = '" .$place . "', ";
		$query .= " city_sensor = '" .$city . "', ";
		$query .= " country_sensor = '" .$country . "', ";
		$query .= " owner_sensor = '" .$owner . "' ";
		$query .= " WHERE id_sensor = " . $sensor;
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