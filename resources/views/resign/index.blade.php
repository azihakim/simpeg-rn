@extends('master')
@section('act-resign', 'active')
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
				<h3 class="card-title">Pengunduran Diri</h3>

				<div class="card-tools">
					<div class="btn-group">
						@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Karyawan')
							<a href="{{ route('resign.create') }}" class="btn btn-outline-primary btn-icon-text">
								<i class="fa fa-plus-square btn-icon-prepend"></i> Tambah Pengajuan Resign</a>
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
							<th>Tanggal Pengajuan</th>
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
								<th>Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->user->nama }}</td>
								<td>
									@if ($item->status == 'Menunggu')
										<span class="badge badge-warning">{{ $item->status }}</span>
									@elseif($item->status == 'Diterima')
										<span class="badge badge-success">{{ $item->status }}</span>
									@else
										<span class="badge badge-danger">{{ $item->status }}</span>
									@endif
								</td>
								<td>{{ $item->created_at->format('d F Y') }}</td>
								@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
												aria-haspopup="true" aria-expanded="false">
												Aksi
											</button>
											<div class="dropdown-menu">
												@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
													<h6 class="dropdown-header">Ubah Status</h6>
													<form action="{{ route('resign.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Ditolak">
														<button class="dropdown-item" type="submit">Tolak</button>
													</form>
													<form action="{{ route('resign.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Diterima">
														<button class="dropdown-item" type="submit">Terima</button>
													</form>
												@endif
												@if (Auth::user()->jabatan == 'Super Admin')
													<div class="dropdown-divider"></div>
													<a href="{{ route('resign.edit', $item->id) }}" class="dropdown-item">Edit</a>
													<form action="{{ route('resign.destroy', $item->id) }}" method="POST" class="d-inline">
														@csrf
														@method('delete')
														<button class="dropdown-item" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">Hapus</button>
													</form>
												@endif
											</div>
										</div>
										@if (Auth::user()->jabatan == 'Super Admin')
											<a href="{{ route('resign.edit', $item->id) }}" class="btn btn-outline-warning">Edit</a>
											<form action="{{ route('resign.destroy', $item->id) }}" method="POST" class="d-inline">
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
