@extends('master')
@section('act-promosidemosi', 'active')
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
				<h3 class="card-title">Promosi/Demosi</h3>

				<div class="card-tools">
					<div class="btn-group">
						@if (Auth::user()->jabatan == 'Super Admin' ||
								Auth::user()->jabatan == 'Admin' ||
								(Auth::user()->jabatan != 'Karyawan' && Auth::user()->jabatan != 'Manajer'))
							<a href="{{ route('promosidemosi.create') }}" class="btn btn-outline-primary">Tambah Promosi/Demosi</a>
						@endif
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Nama</th>
							<th>Jenis</th>
							<th>Jabatan Lama</th>
							<th>Jabatan Baru</th>
							<th>Tanggal</th>
							<th>Status</th>
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin' || Auth::user()->jabatan == 'Manajer')
								<th>Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $item)
							<tr>
								<td>{{ $item->karyawan->nama }}</td>
								<td>{{ $item->jenis }}</td>
								<td>{{ $item->divisiLama->nama_jabatan }}</td>
								<td>{{ $item->divisiBaru->nama_jabatan }}</td>
								<td>{{ $item->created_at->format('d/m/Y') }}</td>
								<td>{{ $item->status }}</td>

								@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin' || Auth::user()->jabatan == 'Manajer')
									<td>
										@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Pimpinan' || Auth::user()->jabatan == 'Admin')
											<a href="{{ route('promosidemosi.edit', $item->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
											<form action="{{ route('promosidemosi.destroy', $item->id) }}" method="POST" class="d-inline">
												@csrf
												@method('delete')
												<button type="submit" class="btn btn-outline-danger btn-sm"
													onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
											</form>
										@endif
										@if (Auth()->user()->jabatan == 'Manajer' || Auth::user()->jabatan == 'Pimpinan')
											<div class="btn-group">
												<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown"
													aria-haspopup="true" aria-expanded="false">
													Ubah Status
												</button>
												<div class="dropdown-menu">
													<h6 class="dropdown-header">Cek Surat</h6>
													<a class="dropdown-item" href="{{ Storage::url($item->surat_rekomendasi) }}" target="_blank">Cek Surat</a>
													<div class="dropdown-divider"></div>
													<h6 class="dropdown-header">Ubah Status</h6>
													<form action="{{ route('promosidemosi.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Ditolak">
														<button class="dropdown-item" type="submit">Tolak</button>
													</form>
													<form action="{{ route('promosidemosi.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Diterima">
														<button class="dropdown-item" type="submit">Terima</button>
													</form>
												</div>
											</div>
										@endif
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
