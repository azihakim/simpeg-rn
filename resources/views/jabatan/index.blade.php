@extends('master')
@section('act-jabatan', 'active')
@section('content')
	<div class="col-sm-6">
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
				<h3 class="card-title">Jabatan</h3>

				<div class="card-tools">
					<div class="btn-group">
						<a href="{{ route('jabatan.create') }}" class="btn btn-outline-primary">Tambah Jabatan</a>
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5px">#</th>
							<th>Jabatan</th>
							<th style="width: 30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->nama_jabatan }}</td>
								<td>
									<div>
										<a href="{{ route('jabatan.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
										<form action="{{ route('jabatan.destroy', $item->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-outline-danger"
												onclick="return confirm('Yakin Hapus Data ini?')">Hapus</button>
										</form>
									</div>
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
