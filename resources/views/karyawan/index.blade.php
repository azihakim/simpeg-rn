@extends('master')
@section('act-karyawan', 'active')
@section('content')
	<div class="col-sm-12">
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
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Karyawan</h3>

				<div class="card-tools">
					<div class="btn-group">
						<a href="{{ route('karyawan.create') }}" class="btn btn-outline-primary">Tambah Karyawn</a>
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5px">#</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>Alamat</th>
							<th>No. Telp</th>
							<th>NIK</th>
							<th style="width: 15%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($karyawan as $karyawan)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $karyawan->nama }}</td>
								<td>{{ $karyawan->jabatan->nama_jabatan }}</td>
								<td>{{ $karyawan->alamat }}</td>
								<td>{{ $karyawan->no_telp }}</td>
								<td>{{ $karyawan->nik }}</td>
								<td>
									<a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
									<form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline;">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-sm">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- /.card-body -->
		</div>
	</div>
@endsection