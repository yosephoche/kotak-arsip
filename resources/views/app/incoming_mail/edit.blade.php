<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Sunting Surat Masuk</title>

	@include('app.layouts.partial.style')

</head>


<body>
	<div class="page-loader">
		<img src="{{ asset('assets/app/img/load.gif') }}" alt="Loading...">
	</div>
	
	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<li class="back"><a href="{{ URL::previous() }}"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Kembali</a></li>
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
								<form action="{{ route('incoming_mail_replace_edit') }}" method="post" id="replaceImage" enctype="multipart/form-data">
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
								<img src="{{ asset('assets/app/img/icons/pdf.png') }}" alt="">
							@else
								<img src="{{ asset('assets/tesseract/').'/'.Auth::user()->_id.'/'.$img.'?'.$rand }}" alt="">
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
							<div><object data="{{ asset('assets/tesseract').'/'.Auth::user()->_id.'/'.$img.'?'.$rand }}" type="text/html"></object></div>
						 @else
							<div><img src="{{ asset('assets/tesseract').'/'.Auth::user()->_id.'/'.$img.'?'.$rand }}" alt="" data-image="{{ $img }}"></div>
						 @endif
					@endforeach
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select">
						<form action="{{ route('incoming_mail_update', ['id' => $archieve->_id]) }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="item">
								<label>Asal Surat</label>
								<div class="value"><input type="text" class="form-control" name="from" value="{{ $archieve->from }}" required></div>
							</div>
							<div class="item">
								<label>Nomor Surat</label>
								<div class="value"><input type="text" class="form-control" name="reference_number" value="{{ $archieve->reference_number }}" autocomplete="off" required></div>
							</div>
							<div class="item">
								<label>Perihal</label>
								<div class="value"><input type="text" class="form-control" name="subject" value="{{ $archieve->subject }}" autocomplete="off" required></div>
							</div>
							<div class="item">
								<?php 
									$format = substr($archieve->date, 0, -3);
									$date =  Carbon\Carbon::createFromTimestamp($format)->format('d/m/Y');
								 ?>
								<label>Tanggal Masuk</label>
								<div class="value"><input type="text" class="form-control" name="date" value="{{ $date }}" id="datepicker" autocomplete="off" required></div>
							</div>
							<div class="item">
								<label>Penyimpanan Arsip <i>(opsional)</i></label>
								<div class="value">
									<select name="storage" id="storage" class="form-control">
										<option value="">Tidak ada</option>
										@foreach ($storage as $s)
											<option value="{{ $s->_id }}" {{ $s->_id == $archieve->storage ? 'selected' : '' }}>{{ $s->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="item" id="subshow">
								<label>Sub Penyimpanan Arsip</label>
								<div class="value">
									<select name="storagesub" id="substorage" class="form-control">
										@foreach (@$storagesub->where('id_storage', $archieve->storage) as $s)
											<option value="{{ $s->_id }}" {{ $s->_id == $archieve->storagesub ? 'selected' : '' }}>{{ $s->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="item">
								<label>Salin ke <a data-toggle="tooltip" data-placement="top" title="Kelompokkan arsip Anda berdasarkan kategori atau kegiatan yang berkaitan dengan arsip ini">Folder</a> <i>(opsional)</i></label>
								<div class="value"><input type="text" class="form-control" name="folder" list="folder" autocomplete="off" value="{{ $archieve->folder }}"></div>
								<datalist id="folder">
									@foreach ($folder as $val)
										<option value="{{ $val->folder }}">
									@endforeach
								</datalist>
							</div>
							<div class="item">
								<label>Keterangan <i>(opsional)</i></label>
								<div class="value"><input type="text" class="form-control" name="note" value="{{ $archieve->note }}" autocomplete="off"></div>
							</div>
							<div class="item">
								<hr>
								<div class="row">
									<div class="col-md-6">
										<a href="{{ route('incoming_mail') }}" class="btn btn-default btn-block">Batal</a>
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

		<!-- Modals -->
		<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="disposisiLabelModal">Disposisi</h4>
						</div>
						<div class="modal-body">
							<table class="table">
								<tr>
									<td class="search" colspan="4"><input type="text" class="form-control" placeholder="Cari" v-model="search"></td>
								</tr>
								<tr v-for="val in filteredUsers">
									<td class="text-center"><input type="checkbox"></td>
									<td><div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + val.photo + ')' }"></div></td>
									<td>
										<span class="name" v-html="val.name"></span><br>
										<span class="position" v-html="val.position"></span>
									</td>
								</tr>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							<button class="btn btn-primary">Disposisi</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('incoming_mail_delete') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="text" class="hidden" id="delete-val" value="">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus data ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
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
				url: '{{ route("incoming_mail_upload_ajax") }}',
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
				url: '{{ route("incoming_mail_delete_ajax") }}',
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

		//Hide Sub Storage
		@if ($archieve->storagesub == '')
			$('#subshow').hide();
		@endif

		//Ajax Dropdown
		$('#storage').on('change', function(e){
			var storage_id = e.target.value;
			//ajax
			$.get('../dropdown?storage_id=' + storage_id, function(data){
				if (data == 0) {
					console.log(data);
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