@extends('master')
@section('act-promosidemosi', 'active')
@section('content')
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
	<div class="col-sm-7">
		<div class="card card-default">
			<div class="card-header">
				<h3 class="card-title">Form Tambah Promosi/Demosi</h3>
			</div>
			<!-- /.card-header -->
			<!-- form start -->
			<form action="{{ route('promosidemosi.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data"
				style="padding: 20px">
				@csrf
				<div class="row">
					<div class="form-group col-md-6">
						<label>Karyawan</label>
						<select name="id_karyawan" class="form-control select2" id="id_karyawan" style="width: 100%;">
							<option value="">Pilih Karyawan</option>
							@foreach ($karyawan as $item)
								<option value="{{ $item->id }}" data-divisi_lama="{{ $item->divisi ? $item->divisi->nama_jabatan : '-' }}"
									data-divisi_lama_id="{{ $item->divisi_id }}">
									{{ $item->nama }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Promosi/Demosi</label>
						<select required class="form-control select2" name="jenis" style="width: 100%;">
							<option selected disabled>Pilih Jenis</option>
							<option value="Promosi">Promosi</option>
							<option value="Demosi">Demosi</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-6">
						<label>Divisi Lama</label>
						<input type="hidden" name="divisi_lama_id">
						<input type="text" class="form-control" name="divisi_lama_display" disabled>
					</div>
					<div class="form-group col-md-6">
						<label>Divisi Baru</label>
						<select name="divisi_baru_id" class="form-control select2" id="pelamarSelect" style="width: 100%;">
							<option selected disabled>Pilih Divisi Baru</option>
							@foreach ($divisi as $item)
								<option value="{{ $item->id }}">
									{{ $item->nama_jabatan }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<label>Upload Surat</label>
						<input required name="surat_rekomendasi" type="file" class="form-control">
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
		$(document).ready(function() {

			$('#id_karyawan').on('change', function() {
				var selectedOption = $(this).find('option:selected');
				var divisiLama = selectedOption.data('divisi_lama');
				var divisiLamaId = selectedOption.data('divisi_lama_id');
				$('input[name="divisi_lama_id"]').val(divisiLamaId);
				$('input[name="divisi_lama_display"]').val(divisiLama);
			});
		});
	</script>
	<script>
		document.querySelector('#btnCancel').addEventListener('click', function(event) {
			event.preventDefault();
			window.history.back();
		});
	</script>
@endsection
