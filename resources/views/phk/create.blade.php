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
				<h3 class="card-title">Form Tambah PHK</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('phk.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data"
				style="padding: 10px">
				@csrf
				<div class="row">
					<div class="form-group col-md-12">
						<label>Karyawan</label>
						<select required name="id_karyawan" class="form-control" id="id_karyawan" style="width:100%">
							<option value="">Pilih Karyawan</option>
							@foreach ($karyawan as $item)
								<option value="{{ $item->id }}" data-divisi_lama="{{ $item->divisi }}">{{ $item->nama }} -
									{{ $item->divisi->nama_jabatan }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="keterangan">Upload Surat</label>
							<div class="input-group col-xs-12">
								<input required="" name="surat_phk" type="file" class="form-control file-upload-info"
									placeholder="Upload File">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="keterangan">Keterangan</label>
							<textarea required name="keterangan" class="form-control" id="keterangan" style="height: 120px"></textarea>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end">
					<button type="submit" class="btn btn-outline-primary">Simpan</button>
				</div>
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
