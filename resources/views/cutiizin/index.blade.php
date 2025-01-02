@extends('master')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>CUTI/IZIN</h1>
				<p>List Cuti/Izin</p>
			</div>
		</div>
		@if (session('success'))
			<div class="alert alert-success" id="success-alert">
				{{ session('success') }}
			</div>
		@endif
		@if (session('error'))
			<div class="alert danger">
				{{ session('error') }}
			</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between">
							<div>
								<h4 class="card-title">List Cuti/Izin</h4>
							</div>
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Karyawan')
								<div>
									<a href="{{ route('cutiizin.create') }}" class="btn btn-outline-primary btn-icon-text">
										<i class="fa fa-plus-square btn-icon-prepend"></i> Tambah Cuti/Izin</a>
								</div>
							@endif
						</div>
						<table id="example" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Karyawan</th>
									<th>Jabatan</th>
									<th>Status</th>
									<th>Jenis</th>
									<th>Tanggal</th>
									@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
										<th>Aksi</th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $item)
									<tr>
										<td>{{ $item->user->nama }}</td>
										<td>{{ $item->user->jabatan }}</td>
										{{-- <td>{{ $item->status }}</td> --}}
										<td>
											@if ($item->status == 'Menunggu')
												<span class="badge badge-opacity-warning me-3">{{ $item->status }}</span>
											@elseif($item->status == 'Diterima')
												<span class="badge badge-opacity-success me-3">{{ $item->status }}</span>
											@elseif($item->status == 'Ditolak')
												<span class="badge badge-opacity-danger me-3">{{ $item->status }}</span>
											@endif
										</td>
										<td>{{ $item->jenis }}</td>
										<td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} -
											{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
										@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
											<td>
												<div class="dropdown">
													<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuOutlineButton1"
														data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1" style="">
														<h6 class="dropdown-header">Data</h6>
														@if (Auth()->user()->jabatan == 'Super Admin')
															<a href="{{ route('cutiizin.edit', $item->id) }}" class="dropdown-item">Edit</a>
														@endif
														@if (Auth()->user()->jabatan == 'Super Admin')
															<form action="{{ route('cutiizin.destroy', $item->id) }}" method="POST" class="d-inline">
																@csrf
																@method('delete')
																<button type="submit" class="dropdown-item">Hapus</button>
															</form>
														@endif
														<div class="dropdown-divider"></div>

														@if (Auth()->user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Manajer')
															<h6 class="dropdown-header">Ubah Status</h6>
															<form action="{{ route('cutiizin.status', $item->id) }}" method="POST" style="display:inline;">
																@csrf
																@method('PUT')
																<input type="hidden" name="status" value="Ditolak">
																<button class="dropdown-item" type="submit">Tolak</button>
															</form>
															<form action="{{ route('cutiizin.status', $item->id) }}" method="POST" style="display:inline;">
																@csrf
																@method('PUT')
																<input type="hidden" name="status" value="Diterima">
																<button class="dropdown-item" type="submit">Terima</button>
															</form>
														@endif
													</div>
												</div>
											</td>
										@endif
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		new DataTable('#example');
	</script>
	<script>
		setTimeout(function() {
			let successAlert = document.getElementById('success-alert');
			let fadeEffect = setInterval(function() {
				if (!successAlert.style.opacity) {
					successAlert.style.opacity = 1;
				}
				if (successAlert.style.opacity > 0) {
					successAlert.style.opacity -= 0.1;
				} else {
					clearInterval(fadeEffect);
					successAlert.style.display = 'none';
				}
			}, 50);
		}, 4000);
	</script>
@endsection
