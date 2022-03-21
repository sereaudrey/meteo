// Fonction pour afficher la map interactive
function init() {

    let map = L.map('map').setView([45.750, 4.85], 12);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYXVkcmV5c2VyZSIsImEiOiJja3lvY25icDQzeWl4Mm9wYmVubDBoYnl0In0.Ya2eY72sF7OQEeAFKEkv-A'
    }).addTo(map);
    
    var marker = L.marker([45.7834748242527, 4.882772847209447]).addTo(map);
}
