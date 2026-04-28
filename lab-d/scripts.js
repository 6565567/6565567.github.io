const API_KEY = "062ceec0a57eed02892b17e6760706c5";

document.getElementById("weatherBtn").addEventListener("click", () => {
  const city = document.getElementById("cityInput").value;
  if (!city) return;
  getCurrentWeather(city);
  getForecast(city);
});

function getCurrentWeather(city) {
  const xhr = new XMLHttpRequest();
  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}&units=metric&lang=pl`;
  xhr.open("GET", url, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      console.log(data);
      const date = new Date(data.dt * 1000);
      document.getElementById("currentWeather").innerHTML = `
        <div class="card">
          <h2>${data.name}</h2>
          <p>${date.toLocaleString()}</p>
          <p class="temp">${data.main.temp} °C</p>
          <p>Odczuwalna: ${data.main.feels_like} °C</p>
          <p>${data.weather[0].description}</p>
        </div>
      `;
    }
  };
  xhr.send();
}

function getForecast(city) {
  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${API_KEY}&units=metric&lang=pl`;
  fetch(url)
    .then(response => response.json())
    .then(data => {
      console.log(data);
      let output = "";
      const now = new Date();
      const future = data.list.filter(item => {
        return new Date(item.dt_txt) > now;
      });
      for (let i = 0; i < 8 && i < future.length; i++) {
        const item = future[i];
        output += `
          <div class="card">
            <h4>
                ${new Date(item.dt_txt).toLocaleDateString()}
                ${new Date(item.dt_txt).toLocaleTimeString()}
            </h4>
            <p class="temp">${item.main.temp} °C</p>
            <p>${item.weather[0].description}</p>
          </div>
        `;
      }
      document.getElementById("forecast").innerHTML = output;
    })
    .catch(error => console.log(error));
}


