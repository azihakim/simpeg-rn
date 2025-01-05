@extends('master')
@section('act-absensi', 'active')
@section('content')
	<div class="col-sm-12">
		<!-- Pesan Validasi -->
		@if ($errors->any())
			<div class="alert alert-danger">
				{{ $errors->first() }}
			</div>
		@endif
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Absensi</h3>

				<div class="card-tools">
					@if (Auth::user()->jabatan == 'Super Admin' ||
							Auth::user()->jabatan == 'Karyawan' ||
							Auth::user()->jabatan == 'Pimpinan' ||
							Auth::user()->jabatan == 'Admin')
						<div class="">
							@if (Auth::user()->jabatan == 'Super Admin' || Auth::user()->jabatan == 'Karyawan')
								<button type="button" class="btn btn-outline-info" id="btnAbsen" data-toggle="modal" data-target="#exampleModal">
									<i class="fas fa-calendar-alt"></i> Absen
								</button><br>
							@endif
							@if (Auth::user()->jabatan == 'Super Admin' ||
									Auth::user()->jabatan == 'Admin' ||
									(Auth::user()->jabatan != 'Karyawan' && Auth::user()->jabatan == 'Pimpinan'))
								<button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rekapAbsensi">
									Rekap Absen
								</button>
							@endif
						</div>
					@endif

					@include('absensi.modalAbsen')
					@include('absensi.modalRekap')
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width: 5px; text-align:center">#</th>
							<th>Karyawan</th>
							<th>Tanggal</th>
							<th>Keterangan</th>
							<th>Foto</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($dataAbsen as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $item->user->nama ?? '' }}</td>
								<td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>
								<td>{{ $item->keterangan }}</td>
								<td><img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" style="width: 100px; height: auto;">
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
