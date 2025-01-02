@extends('master')
@section('content')
	<div class="container">
		@if (session('success'))
			<div class="alert alert-success" id="success-alert">
				{{ session('success') }}
			</div>
			<script>
				setTimeout(function() {
					document.getElementById('success-alert').style.display = 'none';
				}, 3000);
			</script>
		@endif
		@if (session('error'))
			<div class="alert alert-danger" id="error-alert">
				{{ session('error') }}
			</div>
			<script>
				setTimeout(function() {
					document.getElementById('error-alert').style.display = 'none';
				}, 3000);
			</script>
		@endif
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Form Cuti/Izin</h4>
						<p class="card-description"> Masukkan Data Dengan Benar </p>
						<form action="{{ route('cutiizin.update', $data->id) }}" method="POST" class="forms-sample">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Nama</label>
										<input required disabled value="{{ $data->user->nama }}" type="text" class="form-control" name="nama">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Jabatan</label>
										<input required disabled value="{{ $data->user->jabatan }}" type="text" class="form-control" name="jabatan">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Jenis Pengajuan</label>
										<select required class="form-control" name="jenis">
											<option value="">Pilih Pengajuan</option>
											<option value="Cuti" {{ $data->jenis == 'Cuti' ? 'selected' : '' }}>Cuti</option>
											<option value="Izin" {{ $data->jenis == 'Izin' ? 'selected' : '' }}>Izin</option>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tanggal Dari</label>
										<input required type="date" class="form-control" name="tanggal_mulai" id="tanggal_dari"
											value="{{ $data->tanggal_mulai }}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tanggal Sampai</label>
										<input required type="date" class="form-control" name="tanggal_selesai" id="tanggal_sampai"
											value="{{ $data->tanggal_selesai }}">
									</div>
								</div>

								<div class="col-md-8">
									<div class="form-group">
										<label for="exampleTextarea1">Keterangan</label>
										<textarea required name="keterangan" class="form-control" style="height: 100px">{{ $data->keterangan }}</textarea>
									</div>
								</div>
							</div>

							<div class="d-flex justify-content-end">
								<button type="submit" class="btn btn-outline-primary">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const tanggalDari = document.getElementById("tanggal_dari");
			const tanggalSampai = document.getElementById("tanggal_sampai");

			// Fungsi untuk mengatur batas minimum pada tanggal "Sampai"
			tanggalDari.addEventListener("change", function() {
				// Set batas minimum (min) pada input "Tanggal Sampai"
				tanggalSampai.min = tanggalDari.value;

				// Jika tanggal "Sampai" lebih kecil dari "Tanggal Dari", reset nilainya
				if (tanggalSampai.value < tanggalDari.value) {
					tanggalSampai.value = "";
				}

				// Aktifkan input "Tanggal Sampai" jika "Tanggal Dari" telah dipilih
				tanggalSampai.disabled = !tanggalDari.value;
			});

			// Pastikan input "Tanggal Sampai" dinonaktifkan secara default
			tanggalSampai.disabled = !tanggalDari.value;
		});
	</script>
@endsection
