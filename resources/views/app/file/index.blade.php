@extends('app.layouts.main')

@section('title', 'Berkas')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascName = 'true';
		if (@$_GET['sort'] == 'name' AND @$_GET['asc'] == 'true') {
			$ascName = 'false';
		}

		$ascdate = 'true';
		if (@$_GET['sort'] == 'date' AND @$_GET['asc'] == 'true') {
			$ascdate = 'false';
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('file') }}">Berkas</a></li>
			</ul>
		</div>

		@if (Auth::user()->id_company == '')
			<div class="alert alert-warning">Anda belum mendaftarkan/bergabung perusahaan Anda, <a href="{{ route('setting') }}">klik disini!</a></div>
		@endif

		@if (Auth::user()->phone == '')
			<div class="alert alert-warning">Silahkan lengkapi profil Anda, <a href="{{ route('setting') }}?tab=account">klik disini!</a></div>
		@endif

		<table class="table table-hover" v-if="json.files != ''">
			<thead>
				<tr>
					<th class="{{ @$_GET['sort'] == 'name' ? 'sort' : '' }} no-border">
						<a href="{{ route('file', ['sort' => 'name', 'asc' => $ascName]) }}">Judul Berkas</a>
						@if (@$_GET['sort'] == 'name')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
					<th class="view-tablet-only no-border">Dibagikan</th>
					<th class="view-tablet-only {{ @$_GET['sort'] == 'date' ? 'sort' : '' }} no-border" colspan="2">
						<a href="{{ route('file', ['sort' => 'date', 'asc' => $ascdate]) }}">Tanggal Unggah</a>
						@if (@$_GET['sort'] == 'date')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
				</tr>
			</thead>
			<tbody class="item no-border" v-for="val in json.files" v-on:click="detailSidebar(val, $event)">
				
				<!-- Original File -->
				<tr v-if="val.id_original === null">
					<td>
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'doc'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'docx'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'ppt'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'pptx'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'xls'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'xlsx'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'pdf'" src="{{ url('assets/app/img/icons/pdf-01.png') }}" alt="" height="30px" style="margin-right: 10px" title="PDF">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'jpg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'png'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'jpeg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<a v-bind:href="'{{ route('file_detail') }}/' + val._id" v-html="val.name"></a>
					</td>
					<td class="view-tablet-only" v-if="val.share != ''" width="150px" style="padding-top: 10px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index < 3 && disposisi != null">
								<b-tooltip :content="disposisi.name" placement="bottom">
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + disposisi.photo + ')' }" v-if="disposisi.photo != '' && disposisi.photo != null"></div>
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index == 0 && val.share.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.share.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td class="view-tablet-only" v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a v-bind:href="'{{ route('file_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a href="#" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" v-on:click="idDispositionArray(val.share)">Bagikan</a></li>
							<li v-if="val.id_original === null"><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-desc="val.desc" :data-folder="val.folder">Sunting</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
						</ul>
					</td>
				</tr>

				<tr v-else>
					<td>
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'doc'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'docx'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'ppt'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'pptx'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'xls'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'xlsx'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'pdf'" src="{{ url('assets/app/img/icons/pdf-01.png') }}" alt="" height="30px" style="margin-right: 10px" title="PDF">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'jpg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'png'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)) === 'jpeg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
						<span v-if="val.share_info_shared[val.share_info_shared.findIndex(x => x.share_to.$oid == '{{ Auth::user()->_id }}')].read == 0">
							<b><a v-bind:href="'{{ route('file_detail') }}/' + val._id + '?read_direct=true'" v-html="val.name"></a></b>
						</span>
						<span v-else>
							<a v-bind:href="'{{ route('file_detail') }}/' + val._id" v-html="val.name"></a>
						</span>
					</td>
					<td class="view-tablet-only" v-if="val.shared != ''" width="150px" style="padding-top: 10px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.shared" class="img-disposisi" v-if="index < 3 && disposisi != null">
								<b-tooltip :content="disposisi.name" placement="bottom">
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + disposisi.photo + ')' }" v-if="disposisi.photo != '' && disposisi.photo != null"></div>
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.shared" class="img-disposisi" v-if="index == 0 && val.shared.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.shared.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td class="view-tablet-only" v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a v-bind:href="'{{ route('file_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a href="#" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" :data-owner="val.id_owner" v-on:click="idDispositionArray(val.shared)">Bagikan</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
						</ul>
					</td>
				</tr>
			</tbody>

		</table>

		<div class="text-center" v-else>
			<hr>
			<br>
			<br>
			<br>
			<br>
			<img src="{{ url('assets/app/img/icons') }}/no_file.png" alt="" width="300px">
			<br>
			<br>
			<br>
			Belum ada berkas
		</div>

		<?php
			$link = GlobalClass::removeGetParam('page');
		?>

		@if ($archieve->lastPage() > 1)
		<div class="text-center">
			<hr class="m-t-0">
			<ul class="pagination-custom">
				<li class="{{ ($archieve->currentPage() == 1) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() - 1) }}">Sebelumnya</a>
				</li>
				@for ($i = 1; $i <= $archieve->lastPage(); $i++)
					<li class="{{ ($archieve->currentPage() == $i) ? ' active' : '' }}">
						<a href="{{ $link.'&page='.$i }}">{{ $i }}</a>
					</li>
				@endfor
				<li class="{{ ($archieve->currentPage() == $archieve->lastPage()) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() + 1) }}" >Selanjutnya</a>
				</li>
			</ul>
		</div>
		@endif
	</div>

	@if (Auth::user()->id_company != null)
	<a href="#" data-toggle="modal" data-target="#newModal" class="btn-add-mobile">+</a>
	@endif
	<aside class="ka-sidebar-detail">
		@if (Auth::user()->id_company != null)
			<a href="#" data-toggle="modal" data-target="#newModal" class="btn btn-primary btn-block">Tambah</a>
		@endif

		<br>

		<div class="detail-info">

			<div v-if="detail !== ''">
				<detail :detail="detail"></detail>
			</div>
			<div v-else>
				<no-select></no-select>
			</div>

		</div>
	</aside>		
@endsection

@section('modal')
	<!-- Modals -->
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

	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newLabelModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('file_store') }}" method="post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="newLabelModal">Tambah Berkas</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" name="name" class="form-control" placeholder="Judul Berkas" autocomplete="off" required>
						</div>
						<div class="form-group">
							<textarea name="desc" rows="5" class="form-control" placeholder="Deskripsi"></textarea>
						</div>
						<div class="form-group">
							<input type="file" name="file[]" class="form-control" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" required>
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
						<button class="btn btn-primary">Tambahkan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

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
							<input type="text" name="name" class="form-control" placeholder="Judul Berkas" autocomplete="off" required>
						</div>

						<div class="form-group">
							<textarea name="desc" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
						</div>

						<div class="form-group">
							<input type="text" name="folder" class="form-control" list="folder" autocomplete="off" placeholder="Folder" required>
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
@endsection

@section('template')
	<!-- No select data in table -->
	<template id="no-select">
		<div class="no-select text-center">
			<img src="{{ asset('assets/app/img/icons/select_file.png') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select">
				<div v-for="message in detail.share_info_shared" v-if="message.share_to.$oid == '{{ Auth::user()->id }}'">
					<div class="item item-highlight" v-if="message.message != null">
						<label>Pesan untuk Anda</label>
						<div class="value" v-html="message.message"></div>
					</div>
					<div class="item" v-if="detail.id_owner != null">
						<label>Dibagikan oleh</label>
						<div class="value" v-html="detail.owner[detail.owner.length - 1].name"></div>
					</div>
				</div>
				<div class="item" v-if="detail.name">
					<label>Judul Berkas</label>
					<div class="value" v-html="detail.name"></div>
				</div>
				<div class="item" v-if="detail.desc">
					<label>Deskripsi</label>
					<div v-html="detail.desc"></div>
				</div>
				<div class="item" v-if="detail.folder">
					<label>Folder</label>
					<div class="value"><a :href="'{{ route('folder') }}/' + detail.folder" v-html="detail.folder"></a></div>
				</div>
				<div class="item" v-if="detail.share != ''">
					<label>Dibagikan</label>
					<div class="value value-disposition">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a :href="'{{ route('file_shared_history') }}/' + detail._id" v-html="disposisi.name"></a></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.shared != ''">
					<label>Dibagikan</label>
					<div class="value value-disposition">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.shared" v-html="disposisi.name"></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.date">
					<label>Tanggal Unggah</label>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<a v-bind:href="'{{ route('file_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/berkas.js') }}"></script>
	<script>
		<?php
			/* Sort By */
			$sortKey = '_id';
			if (@$_GET['sort'] == 'name') {
				$sortKey = 'name';
			} else if (@$_GET['sort'] == 'date') {
				$sortKey = 'date';
			}

			/* Ascending or Descending */
			$asc = 'false';
			if (@$_GET['asc'] == 'true') {
				$asc = 'true';
			}

			/* Pagination */
			$page = 1;
			if (@$_GET['page']) {
				$page = $_GET['page'];
			}

		?>
		getDataFiles('{{ route("api_file", ["sort" => $sortKey]) }}&asc={{ $asc }}{{ $page != 1 ? "&page=".$page : "" }}', 'files');

		$('#disposisiModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);

			/* Remove owner mail from disposition */
			var id_owner = $(e.relatedTarget).data('owner');
			if (typeof id_owner !== "undefined") {
				$(this).find('input[value="' + id_owner + '"]').closest('tr').addClass('hide');
			} else {
				$(this).find('tr').removeClass('hide');
			}

			$('#link_history').attr('href', '{{ route('file_shared_history') }}/' + id);
			
			/* Fill val date */
			var now = moment().format('DD/MM/YYYY');
			var status = true;
			$('.val-check').attr('data-date', now);
			$('.val-check').change(function() {
				if (status == true) {
					status = false;
				} else {
					status = true;
				}
				var id = $(this).data('id');
				var date = $(this).data('date');

				$(this).parent().find('input.val-check').val(status == false ? '-' : id);
				$(this).parent().find('input.val-date').val(status == false ? '-' : date);
				$(this).parent().find('input.val-message').val('');
				/* $(this).val(id); */
			});
		});

		/* Edit Modal */
		$('#editModal').on('show.bs.modal', function (e) {
			var key = ['id', 'name', 'desc', 'folder'];
			for (var i = 0; i < key.length; i++) {
				$(this).find('.form-control[name="' + key[i] + '"]').val($(e.relatedTarget).data(key[i]));
			}
		});

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		/* 3 mean 3second */
		alertTimeout(3);
	</script>
@endsection