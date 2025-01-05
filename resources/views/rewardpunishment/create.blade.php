@extends('master')
@section('act-resign', 'active')
@section('content')
	@if (session('error'))
		<div class="alert alert-danger" id="error-alert">
			{{ session('error') }}
		</div>
	@endif
	<div class="col-sm-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Cuti/Izin</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('rewardpunishment.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
				@csrf
				<div class="card-body">
					<div class="form-group row">
						<label for="id_karyawan" class="col-sm-2 col-form-label">Karyawan</label>
						<div class="col-sm-10">
							<select required name="id_karyawan" class="form-control select2" id="id_karyawan" style="width: 100%;">
								<option value="">Pilih Karyawan</option>
								@foreach ($karyawan as $item)
									<option value="{{ $item->id }}" data-divisi_lama="{{ $item->divisi }}"
										data-punishment="{{ $item->has_punishment }}">{{ $item->nama }}</option>
								@endforeach
							</select>
							<span class="right badge badge-danger" id="notifSP" style="display: none;">Karyawan Sudah Pernah Mendapatkan SP
								Sebelumnya</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="jenis" class="col-sm-2 col-form-label">Reward/Punishment</label>
						<div class="col-sm-10">
							<select required class="form-control" name="jenis" id="jenis">
								<option selected disabled value="">Pilih Jenis</option>
								<option value="Reward">Reward</option>
								<option value="Punishment">Punishment</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal" id="tanggal">
						</div>
					</div>
					<div class="form-group row">
						<label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea required name="keterangan" class="form-control" id="keterangan" style="height: 120px"></textarea>
						</div>
					</div>
					<!-- Input untuk Reward -->
					<div class="form-group row" id="divReward" style="display: none;">
						<label for="reward" class="col-sm-2 col-form-label">Reward</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Rp</span>
								</div>
								<input type="number" name="reward" id="reward" class="form-control" placeholder="Reward"
									aria-label="Reward">
							</div>
						</div>
					</div>
					<!-- Input untuk Punishment -->
					<div class="form-group row" id="divSuratPunishment" style="display: none;">
						<label for="surat_punishment" class="col-sm-2 col-form-label">Upload Surat</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="custom-file">
									<input name="surat_punishment" id="surat_punishment" type="file" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" id="btnCancel" class="btn btn-default float-right">Cancel</button>
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
