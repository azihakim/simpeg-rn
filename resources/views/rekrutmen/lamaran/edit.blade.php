@extends('master')
@section('act-karyawan', 'active')
@section('content')
	<div class="col-sm-4">
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
			{{-- <script>
				setTimeout(function() {
					document.getElementById('error-alert').style.display = 'none';
				}, 3000);
			</script> --}}
		@endif
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Form Kirim Lamaran</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('lamaran.update', $data->id) }}" method="POST" enctype="multipart/form-data"
				class="form-horizontal">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="jabatan_id">Jabatan</label>
								<input type="text" value="{{ $data->lowongan->jabatan->nama_jabatan }}" class="form-control" readonly>
								<input type="hidden" name="id_lowongan" value="{{ $data->lowongan->id }}">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="alamat">Deskripsi</label>
								<textarea name="deskripsi" class="form-control" rows="3" readonly>{{ $data->lowongan->deskripsi }}</textarea>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>File CV</label>
								<br>
								<a href="{{ asset('storage/lamaran_files/' . $data->file) }}" target="_blank"
									class="btn btn-outline-info btn-block">Lihat
									CV</a>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Upload CV baru</label>
								<input type="file" class="form-control" name="file" required>
							</div>
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					<button class="btn btn-danger" id="btnCancel">Cancel</button>
					<button type="submit" class="btn btn-info float-right">Simpan</button>
				</div>
				<!-- /.card-footer -->
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
