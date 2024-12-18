@extends('master')
@section('activeNavbar', 'active')
@section('content')
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Absensi</h3>

				<div class="card-tools">
					<div class="btn-group">
						<button type="button" class="btn btn-outline-primary" id="btnAbsen" data-toggle="modal"
							data-target="#exampleModal">Absen</button>
						<button type="button" class="btn btn-outline-warning" id="btnRekap">Rekap</button>

					</div>

					@include('absensi.modalAbsen')
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<table id="example1" class="table table-bordered table-striped">
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
								<td>{{ $item->user->karyawan->nama ?? $item->user->username }}</td>
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
