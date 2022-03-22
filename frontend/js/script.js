// api meteo
const temperatureLocal = document.querySelector(".temperatureLocal");
const humidity = document.querySelector(".humidity");

const sensor = "1";
const url =
  "http://192.168.90.251/assets/api-station/v1/get-release.php?sensor=" +
  sensor;
console.log(url);
let request = new XMLHttpRequest();
request.open("GET", url);
request.responseType = "json";
request.onload = function () {
  releases = request.response;

  // let actualHour = new Date().getHours();
  //       // // icones dynamiques
  //       // if (actualHour >= 6 && actualHour < 22) {
  //       //   imgIcone.src = `styles/assets/jour/${resultatsAPI.current.weather[0].icon}.svg`;
  //       // } else {
  //       //   imgIcone.src = `styles/assets/nuit/${resultatsAPI.current.weather[0].icon}.svg`;
  //       // }

  //last data humidity and temperature
  temperatureLocal.innerText = `${Math.trunc(
    releases[releases.length - 1]["temperature"],
  )}°`;
  humidity.innerText = `${Math.trunc(
    releases[releases.length - 1]["humidity"],
  )}%`;

  // Chart Data
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
