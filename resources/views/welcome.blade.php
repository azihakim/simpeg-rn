<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
		integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

</head>

<body>
	@if (session('latitude') && session('longitude'))
		<script>
			const userLatitude = {{ session('latitude') }};
			const userLongitude = {{ session('longitude') }};
			const message = "{{ session('message') }}";
		</script>
	@else
		<script>
			const userLatitude = null; // Default user latitude
			const userLongitude = null; // Default user longitude
			const message = "Lokasi absen tidak tersedia";
		</script>
	@endif

	<!-- Kantor Latitude & Longitude -->
	<script>
		const officeLatitude = -2.9304; // Ganti dengan koordinat kantor Anda
		const officeLongitude = 104.7173; // Ganti dengan koordinat kantor Anda
		const officeMessage = "Lokasi Kantor";
	</script>

	<div id="map" style="width: 100%; height: 400px;"></div>

	<form id="absensiForm" action="{{ route('absensi.store') }}" method="POST">
		@csrf
		<input type="hidden" name="latitude" id="latitude">
		<input type="hidden" name="longitude" id="longitude">
		<button type="button" onclick="getLocation()">Absen</button>
	</form>

	<!-- Leaflet JS -->
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
		integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

	<script>
		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					document.getElementById('latitude').value = position.coords.latitude;
					document.getElementById('longitude').value = position.coords.longitude;
					document.getElementById('absensiForm').submit();
				});
			} else {
				alert("Geolocation tidak didukung oleh browser ini.");
			}
		}
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Inisialisasi peta
			const map = L.map('map').setView([officeLatitude, officeLongitude], 15);

			// Tambahkan tile layer
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

			// Tambahkan custom icon untuk kantor
			const officeIcon = L.icon({
				iconUrl: 'https://img.icons8.com/?size=100&id=uDLWTqyQkDJQ&format=png&color=000000', // URL ikon (ganti dengan ikon Anda)
				iconSize: [32, 32], // Ukuran ikon [width, height]
				iconAnchor: [16, 32], // Titik jangkar ikon [x, y] (berdasarkan ukuran ikon)
				popupAnchor: [0, -32] // Titik jangkar popup relatif terhadap ikon [x, y]
			});

			// Tambahkan marker kantor dengan ikon khusus
			const officeMarker = L.marker([officeLatitude, officeLongitude], {
					icon: officeIcon
				}).addTo(map)
				.bindPopup(`<b>${officeMessage}</b><br>Lat: ${officeLatitude}, Lng: ${officeLongitude}`);

			// Tambahkan marker absen jika tersedia
			if (userLatitude !== null && userLongitude !== null) {
				const userIcon = L.icon({
					iconUrl: 'https://img.icons8.com/?size=100&id=85yg4VIbT8Gd&format=png&color=000000', // URL ikon (ganti dengan ikon Anda)
					iconSize: [32, 32], // Ukuran ikon [width, height]
					iconAnchor: [16, 32], // Titik jangkar ikon [x, y] (berdasarkan ukuran ikon)
					popupAnchor: [0, -32] // Titik jangkar popup relatif terhadap ikon [x, y]
				});

				const userMarker = L.marker([userLatitude, userLongitude], {
						icon: userIcon
					}).addTo(map)
					.bindPopup(`<b>${message}</b><br>Lat: ${userLatitude}, Lng: ${userLongitude}`).openPopup();

				// Pindahkan peta untuk mencakup kedua lokasi
				const bounds = L.latLngBounds([
					[officeLatitude, officeLongitude],
					[userLatitude, userLongitude]
				]);
				map.fitBounds(bounds);
			} else {
				alert("Koordinat absen tidak tersedia.");
			}
		});
	</script>

</body>

</html>
