// api meteo

const CLEFAPI = "e7898f9bfac6cb1ae2b74dd328bbb92c";

let resultatsAPI;

const actualWeather = document.querySelector(".actual_weather");
const temperatureLocal = document.querySelector(".temperatureLocal");
const imgIcone = document.querySelector(".logo-meteo");
const humidity = document.querySelector(".humidity");

//géolocalisation du navigateur pour avoir le temps
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    (position) => {
      let long = position.coords.longitude;
      let lat = position.coords.latitude;
      AppelAPI(long, lat);
    },
    () => {
      alert(
        "Vous avez refusé la géolacalisation, l'application ne peut pas fonctionner, veuillez l'activer",
      );
    },
  );
}

function AppelAPI(long, lat) {
  fetch(
    `https://api.openweathermap.org/data/2.5/onecall?lat=${lat}&lon=${long}&exclude=minutely&units=metric&lang=fr&appid=${CLEFAPI}`,
  )
    .then((reponse) => {
      return reponse.json();
    })

    .then((data) => {
      // console.log(data);

      resultatsAPI = data;

      actualWeather.innerText = resultatsAPI.current.weather[0].description;
      humidity.innerText = `${resultatsAPI.current.humidity}%`;
      temperatureLocal.innerText = `${Math.trunc(resultatsAPI.current.temp)}°`;

      let actualHour = new Date().getHours();

      // icones dynamiques
      if (actualHour >= 6 && actualHour < 21) {
        imgIcone.src = `styles/assets/jour/${resultatsAPI.current.weather[0].icon}.svg`;
      } else {
        imgIcone.src = `styles/assets/nuit/${resultatsAPI.current.weather[0].icon}.svg`;
      }
    });
}

//GRAPH
const labels = ["10h", "11h", "12h", "13h", "14h", "15h"];

const data = {
  labels: labels,
  datasets: [
    {
      label: "températures",
      backgroundColor: "rgb(255,0,0)",
      borderColor: "rgb(255,0,0)",
      data: [24.73, 24.61, 24.53, 24.56, 24.6, 24.69, 24.76],
    },
    {
      label: "humidité",
      backgroundColor: "rgb(2, 9, 255)",
      borderColor: "rgb(2, 9, 255)",
      data: [28, 27, 26, 27, 28, 28, 29],
    },
  ],
};

const config = {
  type: "line",
  data: data,
  options: {
    title: {
      display: true,
      text: "Relevé de température et d'humidité",
    },
  },
};

const myChart = new Chart(document.getElementById("myChart"), config);
