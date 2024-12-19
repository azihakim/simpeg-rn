@extends('master')
@section('act-karyawan', 'active')
@section('content')
	<div class="col-sm-4">
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
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Lowongan</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('lowongan.update', $lowongan->id) }}" method="POST" enctype="multipart/form-data"
				class="form-horizontal">
				@csrf
				@method('PUT')
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="jabatan_id">Jabatan</label>
								<select name="jabatan_id" class="form-control select2">
									<option selected="selected" disabled></option>
									@foreach ($jabatan as $item)
										<option value="{{ $item->id }}" {{ $item->id == $lowongan->jabatan_id ? 'selected' : '' }}>
											{{ $item->nama_jabatan }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="status">Status</label>
								<select name="status" class="form-control select2">
									<option value="Aktif" {{ $lowongan->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
									<option value="Non Aktif" {{ $lowongan->status == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="deskripsi">Deskripsi</label>
								<textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan prasyarat upload disini">{{ $lowongan->deskripsi }}</textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					<button class="btn btn-danger" id="btnCancel">Cancel</button>
					<button type="submit" class="btn btn-info float-right">Simpan</button>
				</div>
				<!-- /.card-footer -->
			</form>
		</div>
	</div>
@endsection
@section('script')
	<script>
		document.querySelector('#btnCancel').addEventListener('click', function(event) {
			event.preventDefault();
			window.history.back();
		});
	</script>
@endsection
