<!doctype html>

<<<<<<< HEAD
<html lang="en">
=======
		<ul class="list-content">
			<form action="{{ route('incoming_mail_store') }}" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6">
						<div class="form-group hidden">
							<input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">
							<input type="hidden" name="id_company" value="{{ Auth::user()->id_company }}">
							<input type="hidden" name="type" value="incoming_mail">
						</div>
>>>>>>> 5f168c0f7554c46f2b086656d961d7c550d77f40

<head>

	<meta name="keyword" content="">

	<meta name="description" content="">

	<meta name="author" content="">

	<meta property="og:image" content="{{ asset('assets/app/img/logo.svg') }}" />

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<title>Kotakarsip</title>

	<link rel="icon" sizes="16x16" href="{{ asset('assets/app/img/logo.svg') }}" />

	<!-- Font Icon -->
	<link rel="stylesheet" href="{{ asset('assets/app/libs/font-icons/entypo/css/entypo.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/app/libs/font-icons/font-awesome/css/font-awesome.min.css') }}">

	<!-- Bootstrap Vue -->
	<link type="text/css" rel="stylesheet" href="{{ asset('assets/app/css/bootstrap-vue.css') }}"/>

	<!-- Custom css -->
	<link href="{{ asset('assets/app/css/kotakarsip.css') }}" rel="stylesheet">

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
				<form action="{{ route('incoming_mail_upload_ajax') }}" method="post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<label for="files" class="btn btn-default btn-block">+ Tambah file lainnya</label>
					<input type="file" class="hide" name="files[]" id="files" multiple>
					<button>Simpan</button>
				</form>
				<hr>
				<div class="images" v-for="val in json.incomingMail">
					@foreach ($image as $img)
						<div class="pos-r">
							<a href="#" class="delete-img" title="Hapus">×</a>
							<img src="{{ asset('assets/tesseract/image').'/'.$img }}" alt="">
						</div>
					@endforeach
				</div>
			</header>

			<div class="ka-main">
				<div>
					@foreach ($image as $img)
						<img src="{{ asset('assets/tesseract/image').'/'.$img }}" alt="">
					@endforeach
					<!-- <img src="'{{ url(asset('assets/tesseract/image.jpg')) }}'" alt=""> -->
					<!-- <object data="assets/img/data-img/surat-masuk/dok-2.pdf" type=""></object> -->
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select">
						<div class="item">
							<label>Asal Surat</label>
							<div class="value"><input type="text" class="form-control" name="from" value="{{ $from }}"></div>
						</div>
						<div class="item">
							<label>Nomor Surat</label>
							<div class="value"><input type="text" class="form-control" name="reference_number" value="{{ $reference_number[1] }}"></div>
						</div>
						<div class="item">
							<label>Perihal</label>
							<div class="value"><input type="text" class="form-control" name="subject" value="{{ $subject[1] }}"></div>
						</div>
						<div class="item">
							<label>Penyimpanan Arsip</label>
							<div class="value">
								<select name="storage" class="form-control">
									<option value="">Pilih</option>
									<option value="">Filling Cabinet 1</option>
									<option value="">Filling Cabinet 2</option>
									<option value="">Filling Cabinet 3</option>
								</select>
								<!-- <select name="storage" class="form-control m-t-10">
									<option value="">Pilih</option>
									<option value="">Laci 1</option>
									<option value="">Laci 2</option>
									<option value="">Laci 3</option>
									<option value="">Laci 4</option>
								</select> -->
							</div>
						</div>
						<div class="item">
							<label>Tanggal Masuk</label>
							<div class="value"><input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"></div>
						</div>
						<div class="item">
							<label>Keterangan</label>
							<div class="value"><input type="text" class="form-control" name="note"></div>
						</div>
						<input type="hidden" name="fulltext" value="{{ $fulltext }}">
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
					</div>
				</div>
			</aside>
		</section>

<<<<<<< HEAD

		<!-- Modals -->
		<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="disposisiLabelModal">Disposisi</h4>
=======
						<div class="form-footer">
							<a href="{{ route('incoming_mail') }}" class="btn btn-default close-form">Kembali</a>
							<button class="btn btn-primary">Simpan</button>
>>>>>>> 5f168c0f7554c46f2b086656d961d7c550d77f40
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
</body>

</html>

