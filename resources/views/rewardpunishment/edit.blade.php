@extends('master')
@section('act-resign', 'active')
@section('content')
	<div class="col-md-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Form Edit Reward/Punishment</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('rewardpunishment.update', $rewardPunishment->id) }}" method="POST" class="form-horizontal"
				enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="form-group row">
						<label for="id_karyawan" class="col-sm-2 col-form-label">Karyawan</label>
						<div class="col-sm-10">
							<select required name="id_karyawan" class="form-control select2" id="id_karyawan" style="width: 100%;">
								<option value="">Pilih Karyawan</option>
								@foreach ($karyawan as $item)
									<option value="{{ $item->id }}" {{ $item->id == $rewardPunishment->id_karyawan ? 'selected' : '' }}>
										{{ $item->nama }}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="jenis" class="col-sm-2 col-form-label">Reward/Punishment</label>
						<div class="col-sm-10">
							<select required class="form-control" name="jenis" id="jenis">
								<option value="Reward" {{ $rewardPunishment->jenis == 'Reward' ? 'selected' : '' }}>Reward</option>
								<option value="Punishment" {{ $rewardPunishment->jenis == 'Punishment' ? 'selected' : '' }}>Punishment</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal" value="{{ $rewardPunishment->tanggal }}">
						</div>
					</div>
					<div class="form-group row">
						<label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea required name="keterangan" class="form-control" id="keterangan" style="height: 120px">{{ $rewardPunishment->keterangan }}</textarea>
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
								<input type="number" name="reward" id="reward" class="form-control" placeholder="Reward" aria-label="Reward"
									value="{{ $rewardPunishment->reward }}">
							</div>
						</div>
					</div>
					<!-- Input untuk Punishment -->
					<div class="form-group row" id="divSuratPunishment" style="display: none;">
						<label for="surat_punishment" class="col-sm-2 col-form-label">Upload Surat</label>
						<div class="col-sm-10">
							<div class="input-group">
								<div class="custom-file">
									<input name="surat_punishment" id="surat_punishment" type="file" class="custom-file-input">
									<label class="custom-file-label" for="surat_punishment">Choose file</label>
								</div>
							</div>
							@if ($rewardPunishment->surat_punishment)
								<small class="form-text text-muted">
									File saat ini: <a href="{{ Storage::url($rewardPunishment->surat_punishment) }}" target="_blank">Lihat File</a>
								</small>
							@endif
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					<button type="submit" class="btn btn-outline-primary">Perbarui</button>
				</div>
				<!-- /.card-footer -->
			</form>
		</div>
	</div>
@endsection
@section('script')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const jenisSelect = document.getElementById('jenis');
			const divReward = document.getElementById('divReward');
			const divSuratPunishment = document.getElementById('divSuratPunishment');
			const rewardInput = document.getElementById('reward');
			const suratPunishmentInput = document.getElementById('surat_punishment');

			// Fungsi untuk menampilkan input berdasarkan nilai awal
			function showInputBasedOnJenis() {
				const selectedValue = jenisSelect.value;

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
			}

			// Panggil fungsi saat halaman dimuat
			showInputBasedOnJenis();

			// Tambahkan event listener untuk perubahan pada select
			jenisSelect.addEventListener('change', showInputBasedOnJenis);
		});
	</script>
@endsection
