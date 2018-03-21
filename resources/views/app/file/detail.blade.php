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
				<li v-for="val in json.files">
					<div v-if="val.share.length > 0">
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" :data-owner="val.id_owner" v-on:click="idDispositionArray(val.share)">Bagikan</a>
					</div>
					<div v-else>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" :data-owner="val.id_owner" v-on:click="idDispositionArray(val.shared)">Bagikan</a>
					</div>
				</li>
				<li v-for="val in json.files">
					<a :href="'{{ url('files') }}/{{ Auth::user()->id_company }}/file/' + val.files[0]" title="Unduh Berkas" download><i class="fa fa-download"></i></a>
				</li>
				<li class="dropdown" v-for="val in json.files">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li v-if="val.id_original === null"><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-desc="val.desc" :data-folder="val.folder">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.files">
					<div v-if="val.files[0].slice(-3).toLowerCase() == 'pdf'">
						<div><iframe :src="'/pdf/web/viewer.html?file={{ url('files') }}/{{ Auth::user()->id_company }}/file/' + val.files[0]"></iframe></div>
					</div>
					<div v-if="val.files[0].slice(-3).toLowerCase() == 'png' || val.files[0].slice(-3).toLowerCase() == 'jpg' || val.files[0].slice(-4).toLowerCase() == 'jpeg'">
						<div><img :src="'{{ url('files') }}/{{ Auth::user()->id_company }}/file/' + val.files[0]" alt=""></div>
					</div>
					<div v-if="val.files[0].slice(-4).toLowerCase() == 'docx' || val.files[0].slice(-3).toLowerCase() == 'doc' || val.files[0].slice(-3).toLowerCase() == 'ppt' || val.files[0].slice(-4).toLowerCase() == 'pptx' || val.files[0].slice(-3).toLowerCase() == 'xls' || val.files[0].slice(-4).toLowerCase() == 'xlsx'">
						<div><iframe :src="'https://view.officeapps.live.com/op/view.aspx?src={{ url('files') }}/{{ Auth::user()->id_company }}/file/' + val.files[0]"></iframe></div>
					</div>
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select" v-for="val in json.files">
						<div class="item" v-if="val.name">
							<label>Judul Arsip</label>
							<div class="value" v-html="val.name"></div>
						</div>
						<div class="item" v-if="val.desc">
							<label>Deskripsi</label>
							<div v-html="val.desc"></div>
						</div>
						<div class="item" v-if="val.folder">
							<label>Folder</label>
							<div class="value"><a :href="'{{ route('folder') }}/' + val.folder" v-html="val.folder"></a></div>
						</div>
						<div class="item" v-if="val.share != ''">
							<label>Bagikan</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="disposisi in val.share"><a :href="'{{ route('file_shared_history') }}/' + val._id" v-html="disposisi.name"></a></li>
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
		<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="{{ route('file_update') }}" method="post">
						{{ csrf_field() }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="editLabelModal">Sunting</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" class="form-control" name="id">

							<div class="form-group">
								<input type="text" name="name" class="form-control" placeholder="Judul Berkas">
							</div>

							<div class="form-group">
								<textarea name="desc" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
							</div>

							<div class="form-group">
								<input type="text" name="folder" class="form-control" list="folder" autocomplete="off" placeholder="Folder">
								<datalist id="folder">
									@foreach ($folder as $val)
										<option value="{{ $val->folder }}">
									@endforeach
								</datalist>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							<button class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form action="{{ route('file_shared') }}" method="post">
						{{ csrf_field() }}
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="disposisiLabelModal">Bagikan</h4>
						</div>
						<div class="modal-body" style="border-top: 1px solid #ddd">
							<input type="hidden" name="id">
							<div class="col-md-6 view-tablet-only">
								<br>
								<textarea name="message" rows="13" placeholder="Tambahkan pesan (opsional)" class="form-control no-border no-padding no-resize" onchange="$('.message-fill').val($(this).val())"></textarea>
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
											<div class="img-profile" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
											<div class="img-profile" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
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
							<button class="btn btn-primary">Bagikan</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="{{ route('file_delete') }}" method="post">
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
	<script src="{{ asset('assets/app/vue/berkas.js') }}"></script>
	<script>
		getDataFilesDetail('{{ route('api_file_detail', ['id' => $archieve->_id]) }}', 'files');
		
		$('#disposisiModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);

			// Remove owner mail from disposition
			var id_owner = $(e.relatedTarget).data('owner');
			if (typeof id_owner !== "undefined") {
				$(this).find('input[value="' + id_owner + '"]').closest('tr').addClass('hide');
			} else {
				$(this).find('tr').removeClass('hide');
			}
		});

		// Edit Modal
		$('#editModal').on('show.bs.modal', function (e) {
			var key = ['id', 'name', 'desc', 'folder'];
			for (var i = 0; i < key.length; i++) {
				$(this).find('.form-control[name="' + key[i] + '"]').val($(e.relatedTarget).data(key[i]));
			}
		});

		//Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>

</body>

</html>

