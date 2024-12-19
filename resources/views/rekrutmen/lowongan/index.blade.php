@extends('master')
@section('content')
	<div class="col-sm-9">
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
				<h3 class="card-title">Lowongan</h3>

				<div class="card-tools">
					<div class="btn-group">
						<a href="{{ route('lowongan.create') }}" class="btn btn-outline-primary">Tambah Lowongan</a>
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th style="width: 5px">#</th>
							<th>Jabatan</th>
							<th style="width: 20%">Status</th>
							<th style="width: 30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($lowongan as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->jabatan->nama_jabatan }}</td>
								<td>{{ $item->status }}</td>
								<td>
									@if (Auth::user()->role == 'Admin')
										<a href="{{ route('lowongan.edit', $item->id) }}" class="btn btn-warning btn-block">Edit</a>
										<form action="{{ route('lowongan.destroy', $item->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-block"
												onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
										</form>
									@endif
									@if (Auth::user()->role == 'Pelamar')
										<a href="{{ route('lamaran.regist', $item->id) }}" class="btn btn-info btn-block">Lamar</a>
									@endif
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
