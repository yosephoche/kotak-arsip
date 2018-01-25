<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Detail Arsip</title>

	@include('app.layouts.partial.style')

</head>

<?php
	$type = "";
	$key = "";
	$key_json = "";
	$disposition = "";
	if (Request::is('berbagi/surat/masuk*')) {
		$type = "Masuk";
		$key = "incoming_mail";
		$key_json = "incomingMail";
		$disposition = "Disposisi";
		$key_disposition = "disposition";
	} elseif (Request::is('berbagi/surat/keluar*')) {
		$type = "Keluar";
		$key = "outgoing_mail";
		$key_json = "outgoingMail";
		$disposition = "Bagikan";
		$key_disposition = "shared";
	} elseif (Request::is('berbagi/berkas*')) {
		$type = "Berkas";
		$key = "file";
		$key_json = "files";
		$disposition = "Bagikan";
		$key_disposition = "shared";
	}
?>

<body>
	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<li class="back"><a href="{{ route('shared_'.$key) }}"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Berbagi</a></li>
			</ul>
			<ul class="right-side">
				<li class="dropdown" v-for="val in json.{{ $key_json }}">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus Akses Saya</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.{{ $key_json }}">
					<div v-if="val.type != 'file'">
						<div v-for="image in val.files">
							<div v-if="image.slice(-3) == 'pdf'">
								<object :data="'{{ asset('assets/app/img/'.$key) }}/' + image" type="application/pdf"></object>
							</div>
							<div v-if="image.slice(-3) == 'png' || image.slice(-3) == 'jpg' || image.slice(-3) == 'peg'">
								<img :src="'{{ asset('assets/app/img/'.$key) }}/' + image" alt="">
							</div>
						</div>
					</div>
					<div v-else>
						<div v-if="val.files.slice(-3) == 'pdf'">
							<div><object :data="'{{ asset('assets/app/img/files') }}/' + val.files" type="application/pdf"></object></div>
						</div>
						<div v-if="val.files.slice(-3) == 'png' || val.files.slice(-3) == 'jpg' || val.files.slice(-4) == 'jpeg'">
							<div><img :src="'{{ asset('assets/app/img/files') }}/' + val.files" alt=""></div>
						</div>
						<div v-if="val.files.slice(-4) == 'docx' || val.files.slice(-3) == 'doc' || val.files.slice(-3) == 'ppt' || val.files.slice(-4) == 'pptx' || val.files.slice(-3) == 'xls' || val.files.slice(-4) == 'xlsx'">
							<div>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<!-- <img src="" alt="Pratinjau belum mendukung format berkas"> -->
								<p>Pratinjau belum mendukung format berkas, silahkan <a :href="'{{ asset('assets/app/img/files') }}/' + val.files" title="Unduh Berkas" download>unduh berkas</a>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select" v-for="val in json.{{ $key_json }}">
						@if (Request::is('berbagi/surat/masuk*'))
						<div class="item" v-if="val.from">
							<label>Asal Surat</label>
							<div class="value" v-html="val.from"></div>
						</div>
						@elseif (Request::is('berbagi/surat/keluar*'))
						<div class="item" v-if="val.to">
							<label>Tujuan Surat</label>
							<div class="value" v-html="val.to"></div>
						</div>
						@elseif (Request::is('berbagi/berkas*'))
						<div class="item" v-if="val.name">
							<label>Judul Arsip</label>
							<div class="value" v-html="val.name"></div>
						</div>

						<div class="item" v-if="val.desc">
							<label>Deskripsi</label>
							<div v-html="val.desc"></div>
						</div>
						@endif

						@if (Request::is('berbagi/surat*'))
						<div class="item" v-if="val.reference_number">
							<label>Nomor Surat</label>
							<div class="value" v-html="val.reference_number"></div>
						</div>
						<div class="item" v-if="val.subject">
							<label>Perihal</label>
							<div class="value" v-html="val.subject"></div>
						</div>
						<div class="item" v-if="val.storage != ''">
							<label>Penyimpanan Arsip</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="storage in val.storage"><div class="value" v-html="storage.name"></div></li>
								</ul>
							</div>
						</div>
						<div class="item" v-if="val.storagesub != ''">
							<label>Sub Penyimpanan Arsip</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="storage in val.storagesub"><div class="value" v-html="storage.name"></div></li>
								</ul>
							</div>
						</div>
						@endif

						<div class="item" v-if="val.share[0].user != ''">
							<label>{{ $disposition }}</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="disposisi in val.share"><a :href="'{{ route('shared_'.$key.'_'.$key_disposition.'_history') }}/' + val._id" v-html="disposisi.user[0].name"></a></li>
								</ul>
							</div>
						</div>

						<div class="item" v-if="val.date">
							<label>Tanggal {{ $type }}</label>
							<div class="value" v-html="$options.filters.moment(val.date.$date.$numberLong)"></div>
						</div>
					</div>
				</div>
			</aside>
		</section>

		<!-- Modal -->
		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="{{ route('shared_'.$key.'_delete_detail') }}" method="post">
						{{ csrf_field() }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="deleteLabelModal">Hapus Akses</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" name="id">
							Apakah Anda yakin ingin menghapus akses Anda ke data ini?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							<button type="submit" class="btn btn-danger">Ya, hapus</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- end modal -->
	</div>

	@include('app.layouts.partial.script')
	
	@if (Request::is('berbagi/surat/masuk*'))
		<script src="{{ asset('assets/app/vue/surat-masuk.js') }}"></script>
		<script>
			getDataIncomingMailDetail('{{ route('api_shared_incoming_mail_detail', ['id' => $archieve->_id]) }}', 'incomingMail');

			//Delete Modal
			$('#deleteModal').on('show.bs.modal', function (e) {
				var id = $(e.relatedTarget).data('id');
				$(this).find('input[name="id"]').val(id);
			});
		</script>
	@elseif (Request::is('berbagi/surat/keluar*'))
		<script src="{{ asset('assets/app/vue/surat-keluar.js') }}"></script>
		<script>
			getDataOutgoingMailDetail('{{ route('api_shared_outgoing_mail_detail', ['id' => $archieve->_id]) }}', 'incomingMail');
			
			//Delete Modal
			$('#deleteModal').on('show.bs.modal', function (e) {
				var id = $(e.relatedTarget).data('id');
				$(this).find('input[name="id"]').val(id);
			});
		</script>
	@elseif (Request::is('berbagi/berkas*'))
		<script src="{{ asset('assets/app/vue/berkas.js') }}"></script>
		<script>
			getDataFilesDetail('{{ route('api_shared_file_detail', ['id' => $archieve->_id]) }}', 'files');
			
			//Delete Modal
			$('#deleteModal').on('show.bs.modal', function (e) {
				var id = $(e.relatedTarget).data('id');
				$(this).find('input[name="id"]').val(id);
			});
		</script>
	@endif

</body>

</html>

