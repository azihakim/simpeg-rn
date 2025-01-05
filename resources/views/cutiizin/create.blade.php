@extends('master')
@section('act-jabatan', 'active')
@section('content')
	<div class="col-sm-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Cuti/Izin</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('cutiizin.store') }}" method="POST" class="form-horizontal">
				@csrf
				<div class="card-body">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Nama</label>
						<div class="col-sm-10">
							<input required disabled value="{{ Auth::user()->nama }}" type="text" class="form-control" name="nama">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jabatan</label>
						<div class="col-sm-10">
							<input required disabled value="{{ Auth::user()->jabatan }}" type="text" class="form-control" name="jabatan">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis Pengajuan</label>
						<div class="col-sm-10">
							<select required class="form-control" name="jenis">
								<option value="">Pilih Pengajuan</option>
								<option value="Cuti">Cuti</option>
								<option value="Izin">Izin</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tanggal Dari</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal_mulai" id="tanggal_dari">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tanggal Sampai</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal_selesai" id="tanggal_sampai">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea required name="keterangan" class="form-control" style="height: 100px"></textarea>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" id="btnCancel" class="btn btn-default float-right">Batal</button>
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
@endsection
