@extends('master')
@section('act-cutiizin', 'active')
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
				<h3 class="card-title">Cuti/Izin</h3>

				<div class="card-tools">
					<div class="btn-group">
						@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Karyawan')
							<a href="{{ route('cutiizin.create') }}" class="btn btn-outline-primary">Tambah Cuti/Izin</a>
						@endif
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
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
										<div class="btn-group">
											<button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
												aria-haspopup="true" aria-expanded="false">
												Aksi
											</button>
											<div class="dropdown-menu">
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
												<!-- Tambahkan tombol untuk mengecek kuota cuti izin -->
												<button class="dropdown-item check-quota" data-id="{{ $item->user->id }}">Cek Kuota Cuti</button>
											</div>
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

@section('script')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Panggil fungsi untuk menambahkan event listener
			addCheckQuotaEventListeners();
		});

		function addCheckQuotaEventListeners() {
			// Tambahkan event listener untuk tombol cek kuota
			document.querySelectorAll('.check-quota').forEach(function(button) {
				button.addEventListener('click', function() {
					const idKaryawan = this.getAttribute('data-id');

					// Lakukan AJAX request ke route checkQuota
					fetch(`/check-quota/${idKaryawan}`)
						.then(response => response.json())
						.then(data => {
							// Tampilkan SweetAlert dengan sisa kuota
							Swal.fire({
								title: 'Sisa Kuota Cuti',
								html: `Total cuti yang sudah diambil: ${data.totalRecently} hari<br>Sisa kuota cuti: ${data.remaining_quota} hari`,
								icon: 'info',
								confirmButtonText: 'OK'
							});
						})
						.catch(error => {
							console.error('Error:', error);
						});
				});
			});
		}
	</script>
@endsection
