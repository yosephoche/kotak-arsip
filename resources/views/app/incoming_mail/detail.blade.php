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
				<li class="back"><a href="{{ route('incoming_mail') }}"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Surat Masuk</a></li>
			</ul>
			<ul class="right-side">
				<li>
					<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#disposisiModal">Disposisi</a>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-default" id="favorite" @click="favorite"><i class="fa fa-star-o"></i></a>
				</li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="sunting.html">Sunting</a></li>
						<li><a href="#" class="text-danger">Hapus</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.incomingMail">
					<img v-for="image in val.files" :src="'{{ asset('assets/app/img/incoming_mail') }}/' + image" alt="">
					<!-- <object data="assets/img/data-img/surat-masuk/dok-2.pdf" type=""></object> -->
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select" v-for="val in json.incomingMail">
						<div class="item" v-if="val.from">
							<label>Asal Surat</label>
							<div class="value" v-html="val.from"></div>
						</div>
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
						<div class="item" v-if="val.share">
							<label>Disposisi</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="disposisi in val.share"><a href="" v-html="disposisi.name"></a></li>
								</ul>
							</div>
						</div>
						<div class="item" v-if="val.date">
							<label>Tanggal Masuk</label>
							<div class="value" v-html="$options.filters.moment(val.date.$date.$numberLong)"></div>
						</div>
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

	@include('app.layouts.partial.script')
	<script src="{{ asset('assets/app/vue/surat-masuk.js') }}"></script>
	<script>
		getDataIncomingMailDetail('{{ route('api_incoming_mail_detail', ['id' => $archieve->_id]) }}', 'incomingMail');
	</script>

</body>

</html>

