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

		<table class="table table-hover" v-if="json.files != ''">
			<tr>
				<th class="{{ @$_GET['sort'] == 'name' ? 'sort' : '' }}">
					<a href="{{ route('file', ['sort' => 'name', 'asc' => $ascName]) }}">Judul Berkas</a>
					@if (@$_GET['sort'] == 'name')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th>Ekstensi</th>
				<th class="view-tablet-only">Dibagikan</th>
				<th class="view-tablet-only {{ @$_GET['sort'] == 'date' ? 'sort' : '' }}">
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
			<tr class="item" v-for="val in json.files" v-on:click="detailSidebar(val, $event)">
				<td><a v-bind:href="'{{ route('file_detail') }}/' + val._id" v-html="val.name"></a></td>
				<td v-html="val.files.substr((val.files.lastIndexOf('.') +1))"></td>
				<td class="view-tablet-only" v-if="val.share[0].user != ''" width="150px">
					<ul class="list-unstyled disposisi">
						<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index < 3 && disposisi.user[0] != null">
							<b-tooltip v-bind:content="disposisi.user[0].name" placement="bottom">
								<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + disposisi.user[0].photo + ')' }" v-if="disposisi.user[0].photo != ''"></div>
								<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.svg)' }" v-else></div>
							</b-tooltip>
						</li>
						<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index == 0 && val.share.length > 3">
							<div class="img-disposisi" v-html="'+' + (val.share.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
						</li>
					</ul>
				</td>
				<td v-else>-</td>
				<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a v-bind:href="'{{ route('file_detail') }}/' + val._id">Lihat Detail</a></li>
						<li><a href="#" data-toggle="modal" data-target="#disposisiModal" v-bind:data-id="val._id" v-on:click="idDispositionArray(val.share)">Bagikan</a></li>
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-desc="val.desc" :data-folder="val.folder">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</td>
			</tr>

		</table>

		<div class="text-center" v-else>
			<hr>
			<br>
			<br>
			<br>
			<br>
			<img src="{{ url('assets/app/img/icons') }}/no_file.svg" alt="" width="400px">
			<br>
			<br>
			<br>
			Belum ada data berkas
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

	<aside class="ka-sidebar-detail">
		<a href="#" data-toggle="modal" data-target="#newModal" class="btn btn-primary btn-block">Tambah</a>

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
						<div class="col-md-6">
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
										<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
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
										<div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
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
							<input type="text" name="name" class="form-control" placeholder="Judul Berkas">
						</div>
						<div class="form-group">
							<textarea name="desc" rows="5" class="form-control" placeholder="Deskripsi"></textarea>
						</div>
						<div class="form-group">
							<input type="file" name="file[]" class="form-control" placeholder="Judul Berkas" accept=".jpg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx">
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
			<img src="{{ asset('assets/app/img/icons/select_file.svg') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select">
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
				<div class="item" v-if="detail.share[0].user != ''">
					<label>Dibagikan</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a :href="'{{ route('file_shared_history') }}/' + detail._id" v-html="disposisi.user[0].name"></a></li>
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
			// Sort By
			$sortKey = 'created_at';
			if (@$_GET['sort'] == 'name') {
				$sortKey = 'name';
			} else if (@$_GET['sort'] == 'date') {
				$sortKey = 'date';
			}

			// Ascending or Descending
			$asc = 'false';
			if (@$_GET['asc'] == 'true') {
				$asc = 'true';
			}

			// Pagination
			$page = 1;
			if (@$_GET['page']) {
				$page = $_GET['page'];
			}

		?>
		getDataFiles('{{ route("api_file", ["sort" => $sortKey]) }}&asc={{ $asc }}&page={{ $page }}', 'files');

		$('#disposisiModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		// Edit Modal
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

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection