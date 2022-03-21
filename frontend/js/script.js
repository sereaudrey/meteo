// api meteo
const CLEFAPI = "e7898f9bfac6cb1ae2b74dd328bbb92c";

let resultatsAPI;

const actualWeather = document.querySelector(".actual_weather");
const temperatureLocal = document.querySelector(".temperatureLocal");
// const imgIcone = document.querySelector(".logo-meteo");
const humidity = document.querySelector(".humidity");

//géolocalisation du navigateur pour avoir le temps et l'heure
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

      // let actualHour = new Date().getHours();
      // // icones dynamiques
      // if (actualHour >= 6 && actualHour < 22) {
      //   imgIcone.src = `styles/assets/jour/${resultatsAPI.current.weather[0].icon}.svg`;
      // } else {
      //   imgIcone.src = `styles/assets/nuit/${resultatsAPI.current.weather[0].icon}.svg`;
      // }

      const temperature = resultatsAPI.current.humidity;
      console.log("temperature", temperature);
    });
}

const sensor = "1";

const url =
  "http://192.168.90.251/assets/api-station/v1/get-release.php?sensor=${sensor}";

// console.log(url);

let request = new XMLHttpRequest();
request.open("GET", url);
request.responseType = "json";
request.onload = function () {
  releases = request.response;
  console.log("hello");

  const xlabels = [];
  for (var i = 0; i < releases.length; i++) {
    xlabels.push(releases[i]["time_release"]);
  }

  const injectTemperature = [];
  for (var j = 0; j < releases.length; j++) {
    injectTemperature.push(releases[j]["temperature"]);
  }

  const injectHumidity = [];
  for (var k = 0; k < releases.length; k++) {
    injectHumidity.push(releases[k]["humidity"]);
  }
  console.log(injectHumidity);

  const data = {
    labels: xlabels,
    datasets: [
      {
        label: "temperature °C",
        borderColor: "rgb(255,0,0)",
        backgroundColor: "rgba(255,0,0, 0.2)",
        data: injectTemperature,
      },
      {
        label: "humidité %",
        borderColor: "rgb(2, 9, 255)",
        backgroundColor: "rgba(2, 9, 255, 0.2)",
        data: injectHumidity,
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
};
request.send();
