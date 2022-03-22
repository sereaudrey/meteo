<?php
/*
	Ce fichier permet d'ajouter un relevé de température et d'humidité dans la base de données.
    Pour utiliser cette méthode, l'utilisateur doit obligatoirement précisé l'id de la sonde qui a
effetué le relevé.
	Pour appeler ce fichier via un site web, on utilise la syntaxe suivante :
		/api-station/v1/add-release.php?sensor={id_sonde}&humidity={humidite}&temperature={temperature}
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
		
		case 'GET':
			
			if (intval($_GET['sensor']) != 0){
                $id_sensor = intval($_GET['sensor']);
                AddRelease($id_sensor);
            } else{
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
		http_response_code(200);
		//Affiche les résultats au format JSON
		echo json_encode($response, JSON_PRETTY_PRINT);
	}

	function http_response_code($code = NULL) {
		if ($code !== NULL) {
			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}
			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			header($protocol . ' ' . $code . ' ' . $text);
			$GLOBALS['http_response_code'] = $code;
		} else {
			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
		}
		return $code;
	}
?>