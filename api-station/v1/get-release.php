<?php
/*
	Ce fichier permet de récupérer des données liés aux relevés des sondes
    Lors de l'appel à ce fichier, l'utiliateur est obligé de préciser l'id de la sonde pour
laquelle il souhaite récupérer des données
	Pour appeler ce fichier via un site web, on utilise les syntaxes suivantes :
		+ si l'utilisateur souhaite récupérer tous les relevés d'une sonde :
			/api-station/v1/get-release.php?sensor={id_sonde}
		+ si l'utilisateur souhaite récupérer un nombre précis de relevés de la sonde (à partir du plus récent) :
			/api-station/v1/get-release.php?sensor={id_sonde}&number={nombre_de_releves_a_recuperer}
        + si l'utilisateur souhaite récupérer les relevés d'une sonde à partir d'une date précise :
            /api-station/v1/get-release.php?sensor={id_sonde}&since={yyyy-mm-dd}
        + si l'utilisateur souhaite récupérer les relevés d'une sonde jusqu'à une date précise :
            /api-station/v1/get-release.php?sensor={id_sonde}&to={yyyy-mm-dd}
    Ces URI sont compatibles entre elles et peuvent être appelés ensemble.
        Ex : /api-station/v1/get-release.php?sensor={id_sonde}&since={yyyy-mm-dd}&to={yyyy-mm-dd}
	Les données récupérées sont renvoyées au format JSON
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
            //L'utilisateur doit récupérer les données d'une seule sonde
            //S'il ne précise pas de sonde un message d'erreur est retourné
            if(empty($_GET["sensor"])){
                 http_response_code(405);
                 echo json_encode(["message" => "URI incomplète"]);
			    break;
            }

            //Récupère le numéro de la sonde pour laquelle l'API doit récupérer des données
            $sensor = intval($_GET["sensor"]);

            //Récupère le nombre d'enregistrement que l'utilisateur souhaite récupérer
            if(!empty($_GET["number"])){
                $number = intval($_GET["number"]);
            } 
            //Si aucun paramètre n'est précisé, récupère tous les enregistrements
            else{
                $number = 0;
            }

            //Récupère la date à partir de laquelle l'utilisateur souhaite récupérer des données
            if(!empty($_GET["since"])){
                $since = $_GET["since"];
            } 
            //Si aucun paramètre n'est précisé, récupère tous les enregistrements
            else{
                $since = "";
            }

            //Récupère la date jusqu'à laquelle l'utilisateur souhaite récupérer des données
            if(!empty($_GET["to"])){
                $to = $_GET["to"];
            } 
            //Si aucun paramètre n'est précisé, récupère tous les enregistrements
            else{
                $to = "";
            }
            
            //Appelle la focntion permettant de récupèrer les données
            getReleases($sensor, $number, $since, $to);
            break;

		default:
			// Requête invalide
			http_response_code(405);
                 	echo json_encode(["message" => "Méthode non acceptée"]);
			break;
	}

//*******************************************************************
//FONCTION   : getReleases
//OBJECTIF   : Récupère les données relative à une sonde
//PARAMETRES : 
//	id_sensor : Le numéro de la sonde pour laquelle il faut récupérer
// 		les relevés.
//	number    : Le nombre d'enregistrements à récupérer.
//	since     : La date à partir de laquelle récupérer les données
//	to        : La date jusqu'à laquelle récupérer des enregistrements
//*******************************************************************

	function getReleases($id_sensor, $number, $since, $to){
		//Définit la connexion à utiliser
		global $conn;

		//Met en place la requête que nous allons exécuter
		$query = "SELECT * FROM RELEASES ";
		$query .= " WHERE id_sensor = ".$id_sensor;
	
		//Si l'utilisateur souhaite récupèrer les données depuis une date précise
		if ($since != "") {
				$query .= " AND date_release >= '" . $since . "' ";
		}

		//Si l'utilisateur souhaite récupèrer les données jusqu'à une date précise
		if ($to != "") {
			$query .= " AND date_release <= '" . $to . "' ";
		}

		//Termine la requête pour trier les données récupérées	
		$query .= " ORDER BY date_release DESC ";


		//Si l'utilisateur souhaite récupérer un nombre précis d'enregistrement
		if($number != 0){
			//On modifie la requête
			$query .= " LIMIT " . $number . " ";
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
	http_response_code(200);
	}
?>
