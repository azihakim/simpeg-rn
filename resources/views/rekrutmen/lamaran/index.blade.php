@extends('master')
@section('content')
	<div class="col-sm-10">
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
				<h3 class="card-title">Lamaran</h3>

				{{-- <div class="card-tools">
					<div class="btn-group">
						<a href="{{ route('lowongan.create') }}" class="btn btn-outline-primary">Tambah Lowongan</a>
					</div>
				</div> --}}
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th style="width: 5px">#</th>
							<th>Jabatan</th>
							<th style="width: 20%">Status</th>
							<th style="width: 30%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($lamaran as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->lowongan->jabatan->nama_jabatan }}</td>
								<td>{{ $item->status }}</td>
								<td>
									@if ($item->status == 'Diajukan' && Auth::user()->role == 'Pelamar')
										<a href="{{ route('lamaran.edit', $item->id) }}" class="btn btn-warning btn-block">Edit</a>
									@endif
									@if (Auth()->user()->role == 'Admin')
										<div class="dropdown">
											<button class="btn btn-outline-info btn-block dropdown-toggle" type="button" id="dropdownMenuOutlineButton1"
												data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Respon</button>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">
												<h6 class="dropdown-header">Cek Lamaran</h6>
												<a class="dropdown-item" href="{{ asset('storage/lamaran_files/' . $item->file) }}" target="_blank">Cek
													Berkas</a>
												<div class="dropdown-divider"></div>

												<h6 class="dropdown-header">Ubah Status</h6>
												<form action="{{ route('lamaran.status', $item->id) }}" method="POST" style="display:inline;">
													@csrf
													@method('PUT')
													<input type="hidden" name="status" value="Ditolak">
													<button class="dropdown-item" type="submit">Tolak</button>
												</form>
												<form action="{{ route('lamaran.status', $item->id) }}" method="POST" style="display:inline;">
													@csrf
													@method('PUT')
													<input type="hidden" name="status" value="Diterima">
													<button class="dropdown-item" type="submit">Terima</button>
												</form>
											</div>
										</div>
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
