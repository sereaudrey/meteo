<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/popin.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
        <!--Lien css pour la map-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <!-- Js pour la map -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
        <script src="js/map.js"></script>

    <title>Météo</title> 
    </head>
    <body onload="init()">
        <div class="popin-connexion" id="popinLogin">
            <form id="formLogin" action="">
                <div class="content-login">
                    <div class="logo-exit">
                        <a onclick="exitPopin()">
                            <button id="exitPopin">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </a>
                    </div>
                    <div class="login-mail">
                        <span>Email : </span>
                        <input type="text" name="mail-login" placeholder="Saisissez votre email"/>
                    </div>
                    <div class="login-mdp">
                        <span>Mot de passe : </span>
                        <input type="password" name="mdp-login" placeholder="Saisissez votre mot de passe"/>
                    </div>
                    <div class="login-button">
                        <button> Créer un compte </button>
                        <button> Connexion </button>
                    </div>
                </div>
            </form>
        </div>
    
        

        <div class="menu">
            <div class="item logo-app">
                <i class="fas fa-star-half-alt"></i> 
                <span>ATMOS</span> 
            </div>
            <div class="item connexion-item">
                <a onclick="connexion()">
                    <button id="buttonLogin">
                        <i class="fas fa-user-circle"></i>
                        <span>Connexion</span>
                    </button> 
                </a>
            </div>
            <div class="item logout-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span> 
            </div>
        </div>
        <div class="content">
            <h1> Bienvenue sur Atmos ! </h1>
            <i class="fa-solid fa-circle-user"></i>
            
            <div class="content-weather">
                <div class="actual temperature">
                    <p>
                    <i class="icone fas fa-thermometer-half"></i> Température : 
                        <!-- <img class="icone icone-temp" src="styles/assets/icones/temperature-low-solid.svg" alt="icone temperature">Température :  -->
                        <span class="temperatureLocal"></span>
                    </p>
                </div>
                <div class=" actual humidity">
                    <p>
                        <i class=" icone fas fa-tint"></i>Humidité :
                        <!-- <img class="icone icone-humidity" src="styles/assets/icones/droplet-solid.svg" alt="icone temperature"> Humidité :  -->
                        <span class="humidity"></span>
                    </p>
                </div>
            </div>
            <div class="chart box">
                <div class="title-graph">
                    <h2>Graphique</h2>
                </div>
                <canvas id="myChart" class="chart-data" ></canvas>
            </div>
            <div class="zone-map">
                <div class="title-map">
                    <h2>Ma sonde</h2> <br> 
                    <p>Villeurbanne</p>
                </div>
                <div class="map_sonde" id="map"></div>
            </div>
        </div>
        

        <!-- chart js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>