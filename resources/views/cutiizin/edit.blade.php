@extends('master')
@section('act-cutiizin', 'active')
@section('content')
	<div class="col-sm-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Form Edit Cuti/Izin</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('cutiizin.update', $data->id) }}" method="POST" class="form-horizontal">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Nama</label>
						<div class="col-sm-10">
							<input required disabled value="{{ $data->user->nama }}" type="text" class="form-control" name="nama">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jabatan</label>
						<div class="col-sm-10">
							<input required disabled value="{{ $data->user->jabatan }}" type="text" class="form-control" name="jabatan">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis Pengajuan</label>
						<div class="col-sm-10">
							<select required class="form-control" name="jenis">
								<option value="">Pilih Pengajuan</option>
								<option value="Cuti" {{ $data->jenis == 'Cuti' ? 'selected' : '' }}>Cuti</option>
								<option value="Izin" {{ $data->jenis == 'Izin' ? 'selected' : '' }}>Izin</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tanggal Dari</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal_mulai" id="tanggal_dari"
								value="{{ $data->tanggal_mulai }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tanggal Sampai</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" name="tanggal_selesai" id="tanggal_sampai"
								value="{{ $data->tanggal_selesai }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Keterangan</label>
						<div class="col-sm-10">
							<textarea required name="keterangan" class="form-control" style="height: 100px">{{ $data->keterangan }}</textarea>
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
