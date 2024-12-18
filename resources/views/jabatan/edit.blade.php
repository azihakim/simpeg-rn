@extends('master')
@section('act-jabatan', 'active')
@section('content')
	<div class="col-sm-5">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Jabatan</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('jabatan.update', $jabatan) }}" method="POST" enctype="multipart/form-data"
				class="form-horizontal">
				@csrf
				@method('PATCH')
				<div class="card-body">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jabatan</label>
						<div class="col-sm-10">
							<input type="text" name="nama_jabatan" class="form-control" placeholder="Masukkan Jabatan" required
								value="{{ $jabatan->nama_jabatan }}">
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					<button class="btn btn-default" id="btnCancel">Cancel</button>
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
