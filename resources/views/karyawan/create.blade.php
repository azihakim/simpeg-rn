@extends('master')
@section('act-karyawan', 'active')
@section('content')
	<div class="col-sm-8">
		@if (session('success'))
			<div class="alert alert-success" id="success-alert">
				{{ session('success') }}
			</div>
		@endif
		@if (session('error'))
			<div class="alert alert-danger" id="error-alert">
				{{ session('error') }}
			</div>
		@endif
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Karyawan</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('karyawan.store') }}" method="POST" class="form-horizontal" style="padding: 10px">
				@csrf
				<div class="row mb-3">
					<label for="pelamarSelect" class="col-sm-4 col-form-label">Calon Karyawan</label>
					<div class="col-sm-8">
						<select name="pelamar" class="form-control select2" id="pelamarSelect" style="width: 100%;">
							<option value="">Pilih Calon Karyawan</option>
							@foreach ($pelamar as $item)
								<option value="{{ $item->user->id }}" data-nama="{{ $item->user->nama }}" data-umur="{{ $item->user->umur }}"
									data-alamat="{{ $item->user->alamat }}" data-telepon="{{ $item->user->telepon }}"
									data-jenis_kelamin="{{ $item->user->jenis_kelamin }}" data-pelamarId="{{ $item->id_pelamar }}">
									{{ $item->user->nama }}
								</option>
							@endforeach
						</select>
						<input type="hidden" name="id_pelamar">
					</div>
				</div>
				<div class="row mb-3">
					<label for="umur" class="col-sm-4 col-form-label">Umur</label>
					<div class="col-sm-8">
						<input required type="text" class="form-control" name="umur" id="umur">
					</div>
				</div>
				<div class="row mb-3">
					<label for="jenis_kelamin" class="col-sm-4 col-form-label">Jenis Kelamin</label>
					<div class="col-sm-8">
						<select required class="form-control" name="jenis_kelamin" id="jenis_kelamin">
							<option value="">Pilih Jenis Kelamin</option>
							<option value="Laki-laki">Laki-laki</option>
							<option value="Perempuan">Perempuan</option>
						</select>
					</div>
				</div>
				<div class="row mb-3">
					<label for="telepon" class="col-sm-4 col-form-label">Telepon</label>
					<div class="col-sm-8">
						<input required type="text" class="form-control" name="telepon" id="telepon">
					</div>
				</div>
				<div class="row mb-3">
					<label for="nik" class="col-sm-4 col-form-label">NIK</label>
					<div class="col-sm-8">
						<input required type="text" class="form-control" name="nik" id="nik">
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-sm-8 offset-sm-4 text-right">
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
@section('script')
	<script>
		$(document).ready(function() {

			// Bind change event using jQuery
			$('#pelamarSelect').on('change', function() {
				const selectedOption = this.options[this.selectedIndex];

				// Update text inputs
				const inputNames = ['nama', 'umur', 'telepon'];
				inputNames.forEach(name => {
					const inputField = document.querySelector(`input[name="${name}"]`);
					if (inputField) {
						inputField.value = selectedOption.getAttribute(`data-${name}`) || '';
					}
				});

				// Update "Jenis Kelamin" select field
				const jenisKelamin = selectedOption.getAttribute('data-jenis_kelamin');
				const jenisKelaminField = document.querySelector(`select[name="jenis_kelamin"]`);
				if (jenisKelaminField) {
					// Set the selected option based on the data-jenis_kelamin attribute
					for (const option of jenisKelaminField.options) {
						option.selected = option.value === jenisKelamin;
					}
				}

				const pelamarId = selectedOption.getAttribute('data-pelamarId');
				const idPelamarField = document.querySelector('input[name="id_pelamar"]');
				if (idPelamarField) {
					idPelamarField.value = pelamarId;
				}
			});
		});
	</script>
	<script>
		document.querySelector('#btnCancel').addEventListener('click', function(event) {
			event.preventDefault();
			window.history.back();
		});
	</script>
@endsection
