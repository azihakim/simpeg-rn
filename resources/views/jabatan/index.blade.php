@extends('master')
@section('act-jabatan', 'active')
@section('content')
	<div class="col-sm-8">
		@if (session('success'))
			<div class="alert alert-success" id="success-alert">
				{{ session('success') }}
			</div>
		@endif
		@if (session('error'))
			<div class="alert alert-danger" id="error-alert">
				{{ session('error') }}
			</div>
		@endif
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Jabatan</h3>

				<div class="card-tools">
					@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
						<div class="btn-group">
							<a href="{{ route('jabatan.create') }}" class="btn btn-outline-primary">Tambah Jabatan</a>
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
							<th>Jabatan</th>
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
								<th style="width: 30%">Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->nama_jabatan }}</td>
								@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
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
