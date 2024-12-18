const officeLatitude = -2.9304; // Ganti dengan koordinat kantor Anda
const officeLongitude = 104.7173; // Ganti dengan koordinat kantor Anda
const officeMessage = "Lokasi Kantor";

document.addEventListener("DOMContentLoaded", function () {
    // const userLatitude = document.querySelector('input[name="latitude"]').value;
    // const userLongitude = document.querySelector(
    //     'input[name="longitude"]'
    // ).value;

    // Inisialisasi peta
    const map = L.map("map").setView([officeLatitude, officeLongitude], 15);

    // Tambahkan tile layer
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Tambahkan custom icon untuk kantor
    const officeIcon = L.icon({
        iconUrl:
            "https://img.icons8.com/?size=100&id=uDLWTqyQkDJQ&format=png&color=000000", // URL ikon (ganti dengan ikon Anda)
        iconSize: [32, 32], // Ukuran ikon [width, height]
        iconAnchor: [16, 32], // Titik jangkar ikon [x, y] (berdasarkan ukuran ikon)
        popupAnchor: [0, -32], // Titik jangkar popup relatif terhadap ikon [x, y]
    });

    // Tambahkan marker kantor dengan ikon khusus
    const officeMarker = L.marker([officeLatitude, officeLongitude], {
        icon: officeIcon,
    })
        .addTo(map)
        .bindPopup(
            `<b>${officeMessage}</b><br>Lat: ${officeLatitude}, Lng: ${officeLongitude}`
        );

    // Tambahkan marker absen jika tersedia
    const latitudeInput = document.getElementById("latitude");
    const longitudeInput = document.getElementById("longitude");

    latitudeInput.addEventListener("change", userAbsen);
    longitudeInput.addEventListener("change", userAbsen);
    function userAbsen() {
        const userIcon = L.icon({
            iconUrl:
                "https://img.icons8.com/?size=100&id=85yg4VIbT8Gd&format=png&color=000000", // URL ikon (ganti dengan ikon Anda)
            iconSize: [32, 32], // Ukuran ikon [width, height]
            iconAnchor: [16, 32], // Titik jangkar ikon [x, y] (berdasarkan ukuran ikon)
            popupAnchor: [0, -32], // Titik jangkar popup relatif terhadap ikon [x, y]
        });

        const userMarker = L.marker([userLatitude, userLongitude], {
            icon: userIcon,
        })
            .addTo(map)
            .bindPopup(
                `<b>${message}</b><br>Lat: ${userLatitude}, Lng: ${userLongitude}`
            )
            .openPopup();

        // Pindahkan peta untuk mencakup kedua lokasi
        const bounds = L.latLngBounds([
            [officeLatitude, officeLongitude],
            [userLatitude, userLongitude],
        ]);
        map.fitBounds(bounds);
    }
});
