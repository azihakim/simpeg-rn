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
		@endif
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Karyawan</h3>

				<div class="card-tools">
					@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan != 'Manajer')
						<div class="btn-group">
							<a href="{{ route('karyawan.create') }}" class="btn btn-outline-primary">Tambah Karyawn</a>
						</div>
					@endif
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5px">#</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>Alamat</th>
							<th>No. Telp</th>
							<th>NIK</th>
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
								<th style="width: 15%">Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $karyawan)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $karyawan->nama }}</td>
								<td>{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</td>
								<td>{{ $karyawan->alamat }}</td>
								<td>{{ $karyawan->telepon }}</td>
								<td>{{ $karyawan->nik }}</td>
								@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
									<td>
										<a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
										<form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-sm">Delete</button>
										</form>
									</td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<!-- /.card-body -->
		</div>
	</div>
@endsection
