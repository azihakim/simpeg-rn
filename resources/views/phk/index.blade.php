@extends('master')
@section('act-phk', 'active')
@section('content')
	<div class="col-sm-12">
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
				<h3 class="card-title">PHK</h3>

				<div class="card-tools">
					<div class="btn-group">
						@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Admin')
							<a href="{{ route('phk.create') }}" class="btn btn-outline-primary btn-icon-text">
								<i class="fa fa-plus-square btn-icon-prepend"></i> Tambah PHK</a>
						@endif
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Nama Karyawan</th>
							<th>Status</th>
							<th>Surat</th>
							<th>Keterangan</th>
							@if (Auth()->user()->jabatan == 'Super Admin' ||
									Auth()->user()->jabatan == 'Manajer' ||
									Auth::user()->jabatan == 'Admin')
								<th>Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->user->nama ?? '' }}</td>
								<td>
									@if ($item->status == 'Menunggu')
										<span class="badge badge-warning">{{ $item->status }}</span>
									@elseif($item->status == 'Diterima')
										<span class="badge badge-success">{{ $item->status }}</span>
									@else
										<span class="badge badge-danger">{{ $item->status }}</span>
									@endif
								</td>
								<td>
									<a href="{{ Storage::url('surat_phk/' . $item->surat) }}" class="btn btn-outline-info" target="_blank">Cek
										Surat</a>
								</td>
								<td>{{ $item->keterangan }}</td>
								@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer' || Auth::user()->jabatan == 'Admin')
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
												aria-haspopup="true" aria-expanded="false">
												Aksi
											</button>
											<div class="dropdown-menu">
												@if (Auth()->user()->jabatan == 'Super Admin' || Auth()->user()->jabatan == 'Manajer')
													<h6 class="dropdown-header">Ubah Status</h6>
													<form action="{{ route('phk.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Ditolak">
														<button class="dropdown-item" type="submit">Tolak</button>
													</form>
													<form action="{{ route('phk.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Diterima">
														<button class="dropdown-item" type="submit">Terima</button>
													</form>
												@endif
												<div class="dropdown-divider"></div>
												@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Pengadaan' || Auth::user()->jabatan == 'Admin')
													<a href="{{ route('phk.edit', $item->id) }}" class="dropdown-item">Edit</a>
													<form action="{{ route('phk.destroy', $item->id) }}" method="POST" class="d-inline">
														@csrf
														@method('delete')
														<button class="dropdown-item" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">Hapus</button>
													</form>
												@endif
											</div>
										</div>
										@if (Auth()->user()->jabatan == 'Super Admin' && Auth::user()->jabatan != 'Pimpinan')
											<a href="{{ route('phk.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
											<form action="{{ route('phk.destroy', $item->id) }}" method="POST" class="d-inline">
												@csrf
												@method('delete')
												<button class="btn btn-outline-danger"
													onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">Hapus</button>
											</form>
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
