let map = L.map('map').setView([53.430127, 14.564802], 18);

//--- biore tutaj inne niz z gita bo musialbym do repo wrzucic leafleta zeby ---
//--- z tej funkcji .provider skorzystac ---
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    maxZoom: 19,
    attribution: "Tiles © Esri"
  }
).addTo(map);

let marker = L.marker([53.430127, 14.564802]).addTo(map);

document.getElementById("getLocation").addEventListener("click", function() {
  if (!navigator.geolocation) {
    alert("No geolocation.");
    return;
  }

  navigator.geolocation.getCurrentPosition(position => {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;
    document.getElementById("coords").innerText =
      "Lat: " + lat.toFixed(5) + ", Lon: " + lon.toFixed(5);
    map.setView([lat, lon]);
    if (marker) {
      map.removeLayer(marker);
    }
    marker = L.marker([lat, lon]).addTo(map)
  });
});

document.getElementById("saveButton").addEventListener("click", function() {
  leafletImage(map, function(err, canvas) {
    if (err) {
      console.error(err);
      return;
    }
    createPuzzleFromCanvas(canvas);
  });
});

const GRID = 4;
let correct = 0;

function createPuzzleFromCanvas(sourceCanvas) {
  const board = document.getElementById("puzzle-board");
  const pieces = document.getElementById("puzzle-pieces");
  board.innerHTML = "";
  pieces.innerHTML = "";
  correct = 0;
  const w = sourceCanvas.width;
  const h = sourceCanvas.height;
  const pw = Math.floor(w / GRID);
  const ph = Math.floor(h / GRID);

  for (let i = 0; i < GRID * GRID; i++) {
    let slot = document.createElement("div");
    slot.classList.add("puzzle-slot");
    slot.dataset.index = i;
    addDropHandlers(slot);
    board.appendChild(slot);
  }

  let arr = [];
  for (let r = 0; r < GRID; r++) {
    for (let c = 0; c < GRID; c++) {
      let index = r * GRID + c;
      let pieceCanvas = document.createElement("canvas");
      pieceCanvas.width = pw;
      pieceCanvas.height = ph;
      let ctx = pieceCanvas.getContext("2d");
      ctx.drawImage(sourceCanvas, c * pw, r * ph, pw, ph, 0, 0, pw, ph);
      let img = document.createElement("img");
      img.src = pieceCanvas.toDataURL();
      let wrap = document.createElement("div");
      wrap.classList.add("puzzle-piece");
      wrap.dataset.index = index;
      wrap.appendChild(img);
      addDragHandlers(wrap);
      arr.push(wrap);
    }
  }
  shuffle(arr);
  arr.forEach(p => pieces.appendChild(p));
}

let dragged = null;

function addDragHandlers(el) {
  el.addEventListener("dragstart", e => {
    dragged = el;
    e.dataTransfer.setData("text", el.dataset.index);
  });
}

function addDropHandlers(slot) {
  slot.addEventListener("dragover", e => e.preventDefault());
  slot.addEventListener("drop", e => {
    e.preventDefault();
    if (!dragged) return;
    if (slot.firstChild) {
      document.getElementById("puzzle-pieces").appendChild(slot.firstChild);
    }
    slot.innerHTML = "";
    slot.appendChild(dragged);
    checkCorrect(slot, dragged);
  });
}

function checkCorrect(slot, piece) {
  if (slot.dataset.index === piece.dataset.index) {
    correct++;
  }
  if (correct === GRID * GRID) {
    alert("Puzzle completed!");
    console.debug("Puzzle completed!");
  }
}

function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    let j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
}
