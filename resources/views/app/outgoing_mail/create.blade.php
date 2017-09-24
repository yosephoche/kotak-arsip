<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Tambah Surat Keluar</title>

	@include('app.layouts.partial.style')

</head>


<body>
	<div class="page-loader">
		<img src="{{ asset('assets/app/img/load.gif') }}" alt="Loading...">
	</div>

	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<li class="back"><a href="{{ route('outgoing_mail') }}"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Surat Keluar</a></li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail ka-body-form">
			<header class="ka-menus">
				<form method="post" id="form" enctype="multipart/form-data">
					<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
					<label for="files" class="btn btn-default btn-block">+ Tambah file lainnya</label>
					<input type="file" class="hide" name="files[]" id="files" accept=".jpg, .png, .jpeg, .pdf" multiple>
				</form>
				<hr>
				<div class="images" id="images">
					@foreach ($image as $key => $img)
						<?php 
							$check = substr($img, -3);
							$rand = rand(111111,999999);
						?>
						<div class="pos-r">
							@if ($key == 0)
								<form action="{{ route('outgoing_mail_replace_ajax') }}" method="post" id="replaceImage" enctype="multipart/form-data">
									<input type="hidden" name="_token" id="delete_token" value="{{ csrf_token() }}">
									<input type="file" class="hide" name="file" id="replace" accept=".jpg, .png, .jpeg, .pdf" onchange="$('.page-loader').fadeIn();$('#replaceImage').submit()" multiple>
									<label for="replace" class="change-img" title="Ganti gambar utama"><i class="fa fa-repeat"></i></label>
								</form>
							@endif
							<form action="a" id="formdelete">
								<input type="hidden" name="_token" id="delete_token" value="{{ csrf_token() }}">
								<button type="button" id="delete" class="delete-img" data-image="{{ $img }}" title="Hapus">×</button>
							</form>
							@if ($check == 'pdf')
								<img src="{{ asset('assets/app/img/icons/pdf.svg') }}" alt="">
							@else
								<img src="{{ asset('assets/tesseract/').'/'.Auth::user()->_id.'/'.$img.'?'.$rand}}" alt="">
							@endif
						</div>
					@endforeach
				</div>
			</header>
			
			<div class="ka-main images">
				<div id="main">
					@foreach ($image as $img)
						<?php 
							$check = substr($img, -3);
							$rand = rand(111111,999999);
						 ?>
						 @if ($check == 'pdf')
							<object data="{{ asset('assets/tesseract').'/'.Auth::user()->_id.'/'.$img }}" type="text/html"></object>
						 @else
							<img src="{{ asset('assets/tesseract').'/'.Auth::user()->_id.'/'.$img.'?'.$rand }}" alt="" data-image="{{ $img }}">
						 @endif
					@endforeach
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info" id="detail">
					<div class="select">
						<form action="{{ route('outgoing_mail_store') }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="item">
								<label>Tujuan Surat</label>
								<div class="value"><input type="text" class="form-control" name="to" value="{{ @ltrim($to) }}" required></div>
							</div>
							<div class="item">
								<label>Nomor Surat</label>
								<div class="value"><input type="text" class="form-control" name="reference_number" value="{{ @ltrim($reference_number) }}" required></div>
							</div>
							<div class="item">
								<label>Perihal</label>
								<div class="value"><input type="text" class="form-control" name="subject" value="{{ @ltrim($subject) }}" required></div>
							</div>
							<div class="item">
								<label>Tanggal Keluar</label>
								<div class="value"><input type="text" class="form-control" name="date" value="{{ date('d/m/Y') }}" id="datepicker"></div>
							</div>
							<div class="item">
								<label>Penyimpanan Arsip <i>(opsional)</i></label>
								<div class="value">
									<select name="storage" id="storage" class="form-control">
										<option value="">Pilih Penyimpanan</option>
										@foreach ($storage as $s)
											<option value="{{ $s->_id }}">{{ $s->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="item" id="subshow">
								<label>Sub Penyimpanan Arsip</label>
								<div class="value">
									<select name="storagesub" id="substorage" class="form-control">
									</select>
								</div>
							</div>
							<div class="item">
								<label>Keterangan <i>(opsional)</i></label>
								<div class="value"><input type="text" class="form-control" name="note"></div>
								<input type="hidden" name="fulltext" value="{{ $fulltext }}">
							</div>
							<div class="item">
								<hr>
								<div class="row">
									<div class="col-md-6">
										<a href="{{ route('outgoing_mail') }}" class="btn btn-default btn-block">Batal</a>
									</div>
									<div class="col-md-6">
										<button class="btn btn-primary btn-block">Simpan</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</aside>
		</section>
	</div>

	<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>
	<script>
		// Date Picker
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		//Upload Multiple Image
		$('#files').on('change', function(e) {
		  e.preventDefault();

		  var files = $('#files')[0].files;
		  var token = $('#token').val();

		  var form = new FormData();

		  form.append('_token', token);
		  for (var i = 0; i < files.length; i++) {
			form.append('file[]', files[i]);
		  }

		  $.ajax({
				url: '{{ route("outgoing_mail_upload_ajax") }}',
				cache: false,
				contentType: false,
				processData: false,
				data: form,
				type: 'post',
				beforeSend: function () {
					$('#images').append('<div class="progress" style="border-radius: 3px;"><div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; margin-left: 0; border-left: 0">Sedang mengunggah...</div></div>');
				},
				success: function(data){
					$('#form')[0].reset();
					$('#images').load(' #images');
					$('#main').load(' #main');
				},error:function(){ 
					alert("Gagal upload, ukuran file terlalu besar");
				} 
			 });
		});

		//delete Image
		$(document).on('click', '#delete', function() {

			var image = $(this).data('image');	
			var token = $('#delete_token').val();	

			var form = new FormData();
			form.append('_token', token);
			form.append('image', image);

			// Remove image view
			$(this).closest('.pos-r').slideUp('fast');
			$('#main img[data-image="'+image+'"]').slideUp('fast');

			$.ajax({
				url: '{{ route("outgoing_mail_delete_ajax") }}',
				contentType: false,
				processData: false,
				method: 'POST',
				data: form,
				success: function(data){
					$('#images').load(' #images');
					$('#main').load(' #main');
				},
				error: function(){
					alert('Telah terjadi kesalahan')
				}
			});
		});

		//Ajax Dropdown
		//Hide Sub Storage
		$('#subshow').hide();

		$('#storage').on('change', function(e){
			var storage_id = e.target.value;
			//ajax
			$.get('dropdown?storage_id=' + storage_id, function(data){
				if (data == 0) {
					$('#subshow').hide();
					$('#subshow').find('select').empty();
				} else {
					$('#subshow').show();
					$('#substorage').empty();
					$.each(data, function(index, substorageObj){
						$('#substorage').append('<option value="'+substorageObj._id+'">'+substorageObj.name+'</option>');
					});
				}
			});
		});

	</script>
</body>

</html>