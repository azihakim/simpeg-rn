@extends('master')
@section('act-phk', 'active')
@section('content')
	@if (session('error'))
		<div class="alert alert-danger" id="error-alert">
			{{ session('error') }}
		</div>
	@endif
	<div class="col-sm-7">
		<div class="card card-danger">
			<div class="card-header">
				<h3 class="card-title">Form Edit PHK</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('phk.update', $data->id) }}" method="POST" enctype="multipart/form-data" style="padding: 10px">
				@csrf
				@method('PUT')
				<div class="form-group">
					<label for="nama">Nama Karyawan</label>
					<input type="text" class="form-control" id="nama" name="nama" value="{{ $data->user->nama }}" readonly>
				</div>
				<div class="form-group">
					<label for="status">Status</label>
					<select class="form-control" id="status" name="status">
						<option value="Terima" {{ $data->status == 'Terima' ? 'selected' : '' }}>Terima</option>
						<option value="Tolak" {{ $data->status == 'Tolak' ? 'selected' : '' }}>Tolak</option>
					</select>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="keterangan">Keterangan</label>
							<textarea required name="keterangan" class="form-control" id="keterangan" style="height: 120px">{{ $data->keterangan }}</textarea>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="surat">Surat PHK</label>
					<input type="file" class="form-control" id="surat" name="surat_phk">
					@if ($data->surat)
						<a href="{{ Storage::url('surat_phk/' . $data->surat) }}" target="_blank">Lihat Surat</a>
					@endif
				</div>
				<button type="submit" class="btn btn-primary">Simpan Perubahan</button>
				<a href="{{ route('phk.index') }}" class="btn btn-secondary">Batal</a>
			</form>
		</div>
	</div>
@endsection
@section('script')
	<script>
		document.querySelector('#btnCancel').addEventListener('click', function(event) {
			event.preventDefault();
			window.history.back();
		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const selectKaryawan = document.getElementById('id_karyawan');
			const notifSP = document.getElementById('notifSP');

			selectKaryawan.addEventListener('change', function() {
				// Ambil elemen option yang dipilih
				const selectedOption = selectKaryawan.options[selectKaryawan.selectedIndex];

				// Periksa atribut data-punishment
				const hasPunishment = selectedOption.getAttribute('data-punishment');

				if (hasPunishment === '1') {
					// Tampilkan notifikasi jika has_punishment = 1
					notifSP.style.display = 'inline';
				} else {
					// Sembunyikan notifikasi jika has_punishment != 1
					notifSP.style.display = 'none';
				}
			});
		});

		document.addEventListener('DOMContentLoaded', function() {
			const jenisSelect = document.getElementById('jenis');
			const divReward = document.getElementById('divReward');
			const divSuratPunishment = document.getElementById('divSuratPunishment');
			const rewardInput = document.getElementById('reward');
			const suratPunishmentInput = document.getElementById('surat_punishment');

			jenisSelect.addEventListener('change', function() {
				const selectedValue = this.value;

				// Reset tampilan dan required
				divReward.style.display = 'none';
				divSuratPunishment.style.display = 'none';
				rewardInput.removeAttribute('required');
				suratPunishmentInput.removeAttribute('required');

				// Tampilkan input berdasarkan jenis yang dipilih dan tambahkan required
				if (selectedValue === 'Reward') {
					divReward.style.display = 'block';
					rewardInput.setAttribute('required', 'required');
				} else if (selectedValue === 'Punishment') {
					divSuratPunishment.style.display = 'block';
					suratPunishmentInput.setAttribute('required', 'required');
				}
			});
		});
	</script>
@endsection
