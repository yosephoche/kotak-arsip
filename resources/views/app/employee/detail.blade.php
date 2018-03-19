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
					<a :href="'{{ url('files') }}/{{ Auth::user()->id_company }}/employee/{{ $archieve->id_employee }}/' + val.files[0]" title="Unduh Berkas" download><i class="fa fa-download"></i></a>
				</li>
				<li class="dropdown" v-for="val in json.files">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-desc="val.desc">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.files">
					<div v-if="val.files[0].slice(-3).toLowerCase() == 'pdf'">
						<div><iframe :src="'/pdf/web/viewer.html?file={{ url('files') }}/{{ Auth::user()->id_company }}/employee/{{ $archieve->id_employee }}/' + val.files[0]"></iframe></div>
					</div>
					<div v-if="val.files[0].slice(-3).toLowerCase() == 'png' || val.files[0].slice(-3).toLowerCase() == 'jpg' || val.files[0].slice(-4).toLowerCase() == 'jpeg'">
						<div><img :src="'{{ url('files') }}/{{ Auth::user()->id_company }}/employee/{{ $archieve->id_employee }}/' + val.files[0]" alt=""></div>
					</div>
					<div v-if="val.files[0].slice(-4).toLowerCase() == 'docx' || val.files[0].slice(-3).toLowerCase() == 'doc' || val.files[0].slice(-3).toLowerCase() == 'ppt' || val.files[0].slice(-4).toLowerCase() == 'pptx' || val.files[0].slice(-3).toLowerCase() == 'xls' || val.files[0].slice(-4).toLowerCase() == 'xlsx'">
						<div>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<img src="{{ asset('assets/app/img/icons') }}/cannot-preview.png" alt="Pratinjau belum mendukung format berkas" width="100px"><br><br>
							<p>Pratinjau belum mendukung format berkas, silahkan <a :href="'{{ url('files') }}/{{ Auth::user()->id_company }}/employee/{{ $archieve->id_employee }}/' + val.files[0]" title="Unduh Berkas" download>unduh berkas</a>.</p>
						</div>
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
						<div class="item" v-if="val.storage[0]">
							<label>Penyimpanan Arsip</label>
							<div class="value" v-html="val.storage[0].name"></div>
						</div>
						<div class="item" v-if="val.storagesub[0]">
							<label>Sub Penyimpanan Arsip</label>
							<div class="value" v-html="val.storagesub[0].name"></div>
						</div>
						<div class="item" v-if="val.date">
							<label>Tanggal Diarsipkan</label>
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
					<form action="{{ route('employee_update') }}" method="post">
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
							<div class="form-group" id="subshow">
								<label>Sub Penyimpanan Arsip</label>
								<div class="value">
									<select name="storagesub" id="substorage" class="form-control">
									</select>
								</div>
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

		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="{{ route('employee_delete') }}" method="post">
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
	<script src="{{ asset('assets/app/vue/arsip-kepegawaian.js') }}"></script>
	<script>
		getDataFilesDetail('{{ route('api_employee_detail', ['id' => $archieve->_id]) }}', 'files');

		// Edit Modal
		$('#editModal').on('show.bs.modal', function (e) {
			var key = ['id', 'name', 'desc'];
			for (var i = 0; i < key.length; i++) {
				$(this).find('.form-control[name="' + key[i] + '"]').val($(e.relatedTarget).data(key[i]));
			}
		});

		//Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		// Storage
		$('#subshow').hide();

		$('#storage').on('change', function(e){
			var storage_id = e.target.value;
			//ajax
			$.get('{{ route("employee_files_substorage") }}?storage_id=' + storage_id, function(data){
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

