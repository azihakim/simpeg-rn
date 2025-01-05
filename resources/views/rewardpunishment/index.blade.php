@extends('master')
@section('act-rewardpunishment', 'active')
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
				<h3 class="card-title">Reward/Punishment</h3>

				<div class="card-tools">
					<div class="btn-group">
						@if (Auth::user()->jabatan == 'Super Admin' ||
								Auth::user()->jabatan != 'Karyawan' ||
								Auth::user()->jabatan == 'Pimpinan')
							<a href="{{ route('rewardpunishment.create') }}" class="btn btn-outline-primary btn-icon-text">
								<i class="fa fa-plus-square btn-icon-prepend"></i> Tambah Reward/Punishment Karyawan</a>
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
							<th>Jenis</th>
							<th>Tanggal</th>
							<th>Reward</th>
							<th>Surat Punishment</th>
							<th>Status</th>
							@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Pimpinan')
								<th>Aksi</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($rewardPunishments as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->karyawan->nama }}</td>
								<td>{{ $item->jenis }}</td>
								<td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
								<td>{{ $item->reward ? 'Rp' . number_format($item->reward, 0, ',', '.') : '-' }}</td>
								<td>
									@if ($item->surat_punishment)
										<a href="{{ Storage::url($item->surat_punishment) }}" target="_blank">Lihat File</a>
									@else
										-
									@endif
								</td>
								<td>{{ $item->status }}</td>
								@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Pimpinan')
									<td>
										@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Pimpinan')
											<a href="{{ route('rewardpunishment.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
											<form action="{{ route('rewardpunishment.destroy', $item->id) }}" method="POST"
												style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-sm btn-danger">Hapus</button>
											</form>
										@endif

										@if (Auth()->user()->jabatan == 'Super Admin' || Auth()->user()->jabatan == 'Pimpinan')
											<div class="btn-group">
												<button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
													aria-haspopup="true" aria-expanded="false">
													Ubah Status
												</button>
												<div class="dropdown-menu">
													<h6 class="dropdown-header">Ubah Status</h6>
													<form action="{{ route('rewardpunishment.status', $item->id) }}" method="POST" style="display:inline;">
														@csrf
														@method('PUT')
														<input type="hidden" name="status" value="Ditolak">
														<button class="dropdown-item" type="submit">Tolak</button>
													</form>
													<form action="{{ route('rewardpunishment.status', $item->id) }}" method="POST" style="display:inline;">
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
