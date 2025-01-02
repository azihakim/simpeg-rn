@extends('master')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Form Pendaftaran</h4>
						<p class="card-description"> Masukkan Data Dengan Benar </p>
						<form action="{{ route('lamaran.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
							@csrf
							<div class="form-group col-md-4">
								<label>Jabatan</label>
								<input type="text" class="form-control" value="{{ $lowongan->jabatan }}" disabled>
								<input type="hidden" name="id_lowongan" class="form-control" value="{{ $lowongan->id }}">
							</div>
							<div class="form-group">
								<label for="exampleTextarea1">Deskripsi</label>
								<textarea disabled name="deskripsi" class="form-control" id="exampleTextarea1" rows="4">{{ $lowongan->deskripsi }}</textarea>
							</div>
							<div class="form-group">
								<label>Upload File</label>
								<p class="card-description">Upload file yang dibutuhkan sesuai persyaratan</p>
								{{-- <input required type="file" name="file" class="file-upload-default"> --}}
								<div class="input-group col-xs-12">
									<input required name="file" type="file" class="form-control file-upload-info" placeholder="Upload File">
								</div>
							</div>
							<div class="d-flex justify-content-end">
								<button type="submit" class="btn btn-outline-primary">Daftar</button>
								{{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
							</div>
							{{-- <button class="btn btn-light">Cancel</button> --}}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
@endsection
