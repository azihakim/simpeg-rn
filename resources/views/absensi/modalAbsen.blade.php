@section('style')
	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
		integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

<!-- Modal Absen -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-md" role="document" id="modalDialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Keterangan Absen</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('absensi.store') }}" method="POST" id="absensiForm" enctype="multipart/form-data">
					@csrf
					<div class="form-group" id="divKeterangan" style="display: none">
						<label for="keteranganAbsen">Keterangan Absen</label>
						<div class="row">
							<div class="col-md-2">
								<div class="form-check">
									<input type="radio" required class="form-check-input" name="optionsRadios" id="optionsRadios1"
										value="masuk">
									<label class="form-check-label" for="optionsRadios1">Masuk</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-check">
									<input type="radio" required class="form-check-input" name="optionsRadios" id="optionsRadios2"
										value="pulang">
									<label class="form-check-label" for="optionsRadios2">Pulang</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="ambilFoto">Ambil Foto Bukti</label>
								<div class="row">
									<div class="col-md-6 mb-1">
										<button type="button" class="btn btn-outline-primary" id="startCamera">Mulai Kamera</button>
										<button type="button" class="btn btn-outline-primary" id="takePhoto" style="display: none"><i
												class="fas fa-camera"></i> Ambil
											Foto</button>
									</div>
									<div class="col-md-9">
										<div>
											<video id="video" width="360" autoplay playsinline style="display: none"></video>
											<img id="preview" alt="Hasil Foto" width="360" style="display: none;">
										</div>
										<input type="hidden" id="locationData" name="locationData">
										<input type="hidden" name="latitude" id="latitude">
										<input type="hidden" name="longitude" id="longitude">
										<input type="hidden" id="photoData" name="photoData">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6" id="divLokasi" style="display: none">
							<div class="form-group">
								<label for="locationLink">Lokasi:</label>

								<div id="map" style="height: 300px; width: 100%"></div>

								<a href="#" id="locationLink" target="_blank" class="d-none">Lihat Lokasi di Google Maps</a>
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-primary">Simpan Absensi</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

@section('script')
	<script>
		const video = document.getElementById('video');
		const photoPreview = document.getElementById('preview');
		const photoDataInput = document.getElementById('photoData');
		const startCamera = document.getElementById('startCamera');
		const takePhoto = document.getElementById('takePhoto');
		const locationData = document.getElementById('locationData');
		const divLokasi = document.getElementById('divLokasi');
		const modalDialog = document.getElementById('modalDialog');
		const latitudeInput = document.getElementById('latitude');
		const longitudeInput = document.getElementById('longitude');
		const divKeterangan = document.getElementById('divKeterangan');
		const officeMessage = "Lokasi Kantor";
		let stream;
		let leafletMap;
		let officeLatitude;
		let officeLongitude;

		async function fetchOfficeLocation() {
			try {
				const response = await fetch('/office-location');
				const data = await response.json();
				return {
					latitude: data.latitude,
					longitude: data.longitude
				};
			} catch (error) {
				console.error('Error fetching office location:', error);
				return {
					// latitude: -2.9737082, // Default latitude
					// longitude: 104.7614673 // Default longitude
				};
			}
		}

		(async () => {
			const officeLocation = await fetchOfficeLocation();
			officeLatitude = officeLocation.latitude;
			officeLongitude = officeLocation.longitude;
			const officeMessage = "Lokasi Kantor";

			// Your existing code that uses officeLatitude and officeLongitude
		})();
		// Fungsi Mulai Kamera
		startCamera.addEventListener('click', async () => {
			video.style.display = 'block';
			stream = await navigator.mediaDevices.getUserMedia({
				video: true
			});
			video.srcObject = stream;
			video.play();

			startCamera.style.display = 'none';
			takePhoto.style.display = 'block';
			photoPreview.style.display = 'none';
		});

		// Fungsi untuk menghitung jarak antara dua koordinat (Haversine Formula)
		function calculateDistance(lat1, lon1, lat2, lon2) {
			const R = 6371; // Radius bumi dalam kilometer
			const dLat = (lat2 - lat1) * Math.PI / 180;
			const dLon = (lon2 - lon1) * Math.PI / 180;
			const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
				Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
				Math.sin(dLon / 2) * Math.sin(dLon / 2);
			const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
			const distance = R * c * 1000; // Hasil dalam meter
			return distance;
		}

		takePhoto.addEventListener('click', () => {
			const canvas = document.createElement('canvas');
			const context = canvas.getContext('2d');
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			context.drawImage(video, 0, 0, canvas.width, canvas.height);

			// Foto ke preview
			const photoData = canvas.toDataURL('image/png');
			photoPreview.src = photoData;
			photoPreview.style.display = 'block';
			photoDataInput.value = photoData;

			// Stop kamera
			stream.getTracks().forEach(track => track.stop());
			video.style.display = 'none';
			takePhoto.style.display = 'none';
			startCamera.style.display = 'block';

			// Ambil lokasi pengguna
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(position => {
					const userLatitude = position.coords.latitude;
					const userLongitude = position.coords.longitude;

					// Isi input hidden
					latitudeInput.value = userLatitude;
					longitudeInput.value = userLongitude;

					// Tampilkan link lokasi
					const locationLink = document.getElementById('locationLink');
					locationLink.href = `https://www.google.com/maps?q=${userLatitude},${userLongitude}`;
					locationData.value = `https://www.google.com/maps?q=${userLatitude},${userLongitude}`;
					locationLink.classList.remove('d-none');

					// Cek jarak dengan kantor
					const distance = calculateDistance(userLatitude, userLongitude, officeLatitude,
						officeLongitude);

					// Cek apakah jarak lebih kecil dari 50 meter
					if (distance <= 10000) {
						// Tampilkan peta
						modalDialog.classList.remove('modal-sm');
						modalDialog.classList.add('modal-xl');
						divLokasi.style.display = 'block';
						map(userLatitude, userLongitude);
					} else {
						alert('Lokasi Anda terlalu jauh dari kantor untuk melakukan absensi.');
						// Reset form atau batalkan pengiriman form
						return;
					}
					divKeterangan.style.display = 'block';
				}, error => {
					alert('Lokasi tidak dapat diakses. Periksa izin lokasi.');
					console.error('Error:', error);
				});
			} else {
				alert('Geolokasi tidak didukung oleh browser ini.');
			}
		});


		// Fungsi Map
		function map() {
			const mapContainer = document.getElementById("map");

			// Hapus instance map jika sudah ada sebelumnya
			if (leafletMap) {
				leafletMap.remove();
				leafletMap = null; // Reset map instance
			}

			// Inisialisasi ulang peta
			leafletMap = L.map(mapContainer).setView([officeLatitude, officeLongitude], 15);

			// Tambahkan tile layer
			L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			}).addTo(leafletMap);

			// Tambahkan marker kantor
			const officeIcon = L.icon({
				iconUrl: "https://img.icons8.com/?size=100&id=uDLWTqyQkDJQ&format=png&color=000000",
				iconSize: [32, 32],
				iconAnchor: [16, 32],
				popupAnchor: [0, -32],
			});

			const officeMarker = L.marker([officeLatitude, officeLongitude], {
					icon: officeIcon
				})
				.addTo(leafletMap)
				.bindPopup(`<b>${officeMessage}</b><br>Lat: ${officeLatitude}, Lng: ${officeLongitude}`);

			// Tambahkan marker user jika lokasi tersedia
			const userLatitude = parseFloat(document.getElementById("latitude").value);
			const userLongitude = parseFloat(document.getElementById("longitude").value);

			if (userLatitude && userLongitude) {
				const userIcon = L.icon({
					iconUrl: "https://img.icons8.com/?size=100&id=85yg4VIbT8Gd&format=png&color=000000",
					iconSize: [32, 32],
					iconAnchor: [16, 32],
					popupAnchor: [0, -32],
				});

				const userMarker = L.marker([userLatitude, userLongitude], {
						icon: userIcon
					})
					.addTo(leafletMap)
					.bindPopup(`<b>Lokasi Anda</b><br>Lat: ${userLatitude}, Lng: ${userLongitude}`)
					.openPopup();

				// Atur view untuk mencakup kedua lokasi
				const bounds = L.latLngBounds([
					[officeLatitude, officeLongitude],
					[userLatitude, userLongitude],
				]);
				leafletMap.fitBounds(bounds);
			} else {
				console.warn("Koordinat pengguna tidak tersedia.");
			}
		}

		// Tampilkan modal keterangan absen saat awal load
		// $(document).ready(function() {
		// 	$('#exampleModal').modal('show');
		// });
	</script>

	{{-- map --}}
	{{-- <script src="{{ asset('js/maps.js') }}"></script> --}}
@endsection
