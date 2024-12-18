@extends('master')
@section('act-karyawan', 'active')
@section('content')
	<div class="col-sm-8">
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
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Karyawan</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
				@csrf
				<div class="card-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="nama">Nama</label>
								<input type="text" name="nama" class="form-control">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="jabatan_id">Jabatan</label>
								<select name="jabatan_id" class="form-control select2">
									<option selected="selected" disabled></option>
									@foreach ($jabatan as $item)
										<option value="{{ $item->id }}">{{ $item->nama_jabatan }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="no_telp">Nomor HP</label>
								<input type="text" name="no_telp" class="form-control">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input type="text" name="alamat" class="form-control">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="nik">NIK</label>
								<input type="text" name="nik" class="form-control">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="nik">Username</label>
								<input type="text" name="username" class="form-control">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="nik">Password</label>
								<input type="text" name="password" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					<button class="btn btn-default" id="btnCancel">Cancel</button>
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
