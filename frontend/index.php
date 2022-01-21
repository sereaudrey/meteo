<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="frontend/styles/style.css">
        <!-- Css pour la map -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
        <!-- Js pour la map -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
        <script src="js/map.js"></script>

        <title>Météo</title>
    </head>
    <body onload="init()">
        <h1> Mon application météo </h1>*

        <form>
            <input placeholder="Rechercher une ville">
        </form>
        <div class="meteo_actuelle">
            <span class="logo"></span>
            <span class="ville"> Nom de la ville </span> </br>
            <span class="temperature"> Température actuelle </span>
        </div>

        <div class="carte_sonde" id="map">
            Voici où se trouve la sonde :
        </div>
        
    </body>
</html>