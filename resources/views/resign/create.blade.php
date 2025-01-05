@extends('master')
@section('act-resign', 'active')
@section('content')
	<div class="col-sm-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Cuti/Izin</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('resign.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data"
				style="padding: 10px;">
				@csrf
				<div class="row">
					<div class="form-group col-md-12">
						<label>Karyawan</label>
						<input type="text" value="{{ auth()->user()->nama }}" class="form-control" disabled>
						<input type="hidden" value="{{ auth()->user()->id }}" class="form-control" name="id_karyawan">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="keterangan">Upload Surat Resign</label>
							<div class="input-group col-xs-12">
								<input required="" name="surat_resign" type="file" class="form-control file-upload-info"
									placeholder="Upload File">
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end">
					<button type="submit" class="btn btn-outline-primary">Simpan</button>
				</div>
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
