<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>Detail Arsip</title>

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
			<ul class="right-side">
				<li v-for="val in json.outgoingMail">
					<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#disposisiModal" v-bind:data-id="val._id" v-on:click="idDispositionArray(val.share)">Bagikan</a>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-default" id="favorite" @click="favorite"><i class="fa fa-star-o"></i></a>
				</li>
				<li class="dropdown" v-for="val in json.outgoingMail">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a v-bind:href="'{{ route('outgoing_mail_move') }}/' + val._id">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.outgoingMail">
					<div v-for="image in val.files">
						<div v-if="image.slice(-3) == 'pdf'">
							<object :data="'{{ asset('assets/app/img/outgoing_mail') }}/' + image" type="application/pdf"></object>
						</div>
						<div v-else>
							<img :src="'{{ asset('assets/app/img/outgoing_mail') }}/' + image" alt="">
						</div>
					</div>
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select" v-for="val in json.outgoingMail">
						<div class="item" v-if="val.to">
							<label>Tujuan Surat</label>
							<div class="value" v-html="val.to"></div>
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
						<div class="item" v-if="val.share[0].user != ''">
							<label>Bagikan</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="bagikan in val.share"><a :href="'{{ route('outgoing_mail_shared_history') }}/' + val._id" v-html="bagikan.user[0].name"></a></li>
								</ul>
							</div>
						</div>
						<div class="item" v-if="val.date">
							<label>Tanggal Keluar</label>
							<div class="value" v-html="$options.filters.moment(val.date.$date.$numberLong)"></div>
						</div>
					</div>
				</div>
			</aside>
		</section>

		
		<!-- Modals -->
		<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="{{ route('outgoing_mail_shared') }}" method="post">
						{{ csrf_field() }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="disposisiLabelModal">Disposisi</h4>
						</div>
						<div class="modal-body" style="border-top: 1px solid #ddd">
							<input type="hidden" name="id">
							<div class="col-md-6">
								<br>
								<textarea name="message" rows="14" placeholder="Tambahkan pesan (opsional)" class="form-control no-border no-padding no-resize" onchange="$('.message-fill').val($(this).val())"></textarea>
							</div>
							<div class="col-md-6 no-padding" style="border-left: 1px solid #ddd; height: 299px; overflow-y: auto">
								<table class="table">
									<tr>
										<td class="search" colspan="4"><input type="text" class="form-control" placeholder="Cari" v-model="search"></td>
									</tr>
									<tr v-for="(val, index) in filteredUsers" v-if="val._id != '{{ Auth::user()->_id }}' && dispositionArray.indexOf(val._id) == -1">
										<td class="text-center">
											<input type="checkbox" :name="'share['+index+']'" :value="val._id">
											<input type="text" :name="'date['+index+']'" value="{{ date('d/m/Y') }}" class="hide">
											<input type="text" :name="'message['+index+']'" class="message-fill hide" value="">
										</td>
										<td>
											<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
											<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.svg)' }" v-else></div>
										</td>
										<td>
											<span class="name" v-html="val.name"></span><br>
											<span class="position" v-html="val.position"></span>
										</td>
									</tr>
									<tr v-for="(val, index) in filteredUsers" v-if="val._id != '{{ Auth::user()->_id }}' && dispositionArray.indexOf(val._id) != -1">
										<td class="text-center">
											<input type="checkbox" :name="'share['+index+']'" :value="val._id" checked onchange="$(this).parent().find('input').val('-')">
											<div v-for="info in dispositionInfo" v-if="info != null && info.user[0]._id.$oid == val._id">
												<input type="text" :name="'date['+index+']'" :value="$options.filters.moment(info.date.$date.$numberLong)" class="hide">
												<input type="text" :name="'message['+index+']'" :value="info.message" class="hide">
											</div>
										</td>
										<td>
											<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
											<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.svg)' }" v-else></div>
										</td>
										<td>
											<span class="name" v-html="val.name"></span><br>
											<span class="position" v-html="val.position"></span>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							<button class="btn btn-primary">Disposisi</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="{{ route('outgoing_mail_delete') }}" method="post">
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
		<!-- end modal -->
	</div>

	@include('app.layouts.partial.script')
	<script src="{{ asset('assets/app/vue/surat-keluar.js') }}"></script>
	<script>
		getDataOutgoingMailDetail('{{ route('api_outgoing_mail_detail', ['id' => $archieve->_id]) }}', 'outgoingMail');
		
		$('#disposisiModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		//Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>

</body>

</html>

