<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Kotakarsip</title>

	@include('app.layouts.partial.style')

</head>


<body>
	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<li class="back"><a href="{{ $_SERVER['HTTP_REFERER'] }}"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Surat Masuk</a></li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail ka-body-form">
			<header class="ka-menus">
				<form method="post" id="form" enctype="multipart/form-data">
					<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
					<label for="files" class="btn btn-default btn-block">+ Tambah file lainnya</label>
					<input type="file" class="hide" name="files[]" id="files" multiple>
				</form>
				<hr>
				<div class="images" id="images">
					@foreach ($image as $img)
						<div class="pos-r">
							<form action="a" id="formdelete">
								<input type="hidden" name="_token" id="delete_token" value="{{ csrf_token() }}">
								<button type="button" id="delete" class="delete-img" data-image="{{ $img }}" title="Hapus">×</button>
								<!-- <a type="button" id="delete"  class="delete-img" title="Hapus">×</a> -->
							</form>
							<img src="{{ asset('assets/tesseract/').'/'.Auth::user()->_id.'/'.$img }}" alt="">
						</div>
					@endforeach
				</div>
			</header>
			
			<div class="ka-main images">
				<div id="main">
					@foreach ($image as $img)
						<img src="{{ asset('assets/tesseract').'/'.Auth::user()->_id.'/'.$img }}" alt="">
					@endforeach
					<!-- <img src="'{{ url(asset('assets/tesseract/image.jpg')) }}'" alt=""> -->
					<!-- <object data="assets/img/data-img/surat-masuk/dok-2.pdf" type=""></object> -->
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select">
						<form action="{{ route('incoming_mail_store') }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="item">
								<label>Asal Surat</label>
								<div class="value"><input type="text" class="form-control" name="from" value="{{ @ltrim($from) }}"></div>
							</div>
							<div class="item">
								<label>Nomor Surat</label>
								<div class="value"><input type="text" class="form-control" name="reference_number" value="{{ @ltrim($reference_number) }}"></div>
							</div>
							<div class="item">
								<label>Perihal</label>
								<div class="value"><input type="text" class="form-control" name="subject" value="{{ @ltrim($subject) }}"></div>
							</div>
							<div class="item">
								<label>Penyimpanan Arsip</label>
								<div class="value">
									<select name="storage" class="form-control">
										<option value="">Pilih</option>
										@foreach ($storage as $s)
											<option value="{{ $s->_id }}">{{ $s->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="item">
								<label>Tanggal Masuk</label>
								<div class="value"><input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"></div>
							</div>
							<div class="item">
								<label>Keterangan</label>
								<div class="value"><input type="text" class="form-control" name="note"></div>
								<input type="hidden" name="fulltext" value="{{ $fulltext }}">
							</div>
							<div class="item">
								<hr>
								<div class="row">
									<div class="col-md-6">
										<a href="" class="btn btn-default btn-block">Batal</a>
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

	<script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>
	<script>
		//Upload Multiple Image
		$('#files').change(function(e) {
		  e.preventDefault();

		  var files = $('#files')[0].files;
		  var token = $('#token').val();

		  var form = new FormData();

		  form.append('_token', token);
		  for (var i = 0; i < files.length; i++) {
		  	form.append('file[]', files[i]);
		  }

		  console.log(form.get('file'));

		  $.ajax({
				url: '{{ route("incoming_mail_upload_ajax") }}',
				cache: false,
				contentType: false,
				processData: false,
				data: form,
				type: 'post',
				success: function(data){
					console.log(data);
					$('#images').load(' #images');
					$('#main').load(' #main');
				},error:function(){ 
					alert("Masukkan File Terlebih Dahulu");
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

			console.log(form.get('image'));
			$.ajax({
				url: '{{ route("incoming_mail_delete_ajax") }}',
				contentType: false,
				processData: false,
				method: 'POST',
				data: form,
				success: function(data){
					console.log(data);
					$('#images').load(' #images');
					$('#main').load(' #main');
				},
				error: function(){
					alert('Telah terjadi kesalahan')
				}
			});
		})

	</script>
</body>

</html>