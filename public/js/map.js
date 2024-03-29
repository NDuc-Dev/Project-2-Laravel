var map = L.map("map").setView([20.9951192, 105.8620143], 17);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var marker = L.marker([20.9951192, 105.8620143]).addTo(map);
marker.bindPopup(
    "Tòa nhà VTC Online<br>Số 18 đường Tam Trinh, quận Hai Bà Trưng, thành phố Hà Nội"
);
