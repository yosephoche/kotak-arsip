@extends('app.layouts.main')

@section('title', 'Folder '.str_replace('_', ' ', $folder))

@section('contents')

	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascSearch = 'true';
		if (@$_GET['sort'] == 'search' AND @$_GET['asc'] == 'true') {
			$ascSearch = 'false';
		}

		$ascSubject = 'true';
		if (@$_GET['sort'] == 'subject' AND @$_GET['asc'] == 'true') {
			$ascSubject = 'false';
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('folder') }}">Folder</a></li>
				<li>{{ str_replace('_', ' ', $folder) }}</li>
			</ul>
		</div>

		<ul class="current-filter">
			@if (isset($_GET['fulltext']))
				<li>Mohon Perlindungan Hukum <a href="">&times;</a></li>
			@endif

			@if (isset($_GET['type']))
				<li>Jenis Arsip: Surat Masuk <a href="">&times;</a></li>
			@endif

			@if (isset($_GET['start']))
				<li>Tanggal 2/10/2017 - 9/10/2017 <a href="">&times;</a></li>
			@endif
		</ul>

		<table class="table table-hover" v-if="json.folder != ''">
			<tr>
				<th class="{{ @$_GET['sort'] == 'search' ? 'sort' : '' }}">
					<a href="{{ route('folder_detail', ['folder' => $folder ,'sort' => 'search', 'asc' => $ascSearch]) }}">Judul Arsip</a>
					@if (@$_GET['sort'] == 'search')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th class="view-tablet-only {{ @$_GET['sort'] == 'subject' ? 'sort' : '' }}">
					<a href="{{ route('folder_detail', ['folder' => $folder ,'sort' => 'subject', 'asc' => $ascSubject]) }}">Keterangan</a>
					@if (@$_GET['sort'] == 'subject')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th width="150px">Jenis Arsip</th>
			</tr>
			<tr class="item" v-for="val in json.folder" @click="detailSidebar(val, $event)">
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id" v-html="val.search"></a>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + val._id" v-html="val.search"></a>
					</div>
					<div v-if="val.type == 'file'">
						<a v-bind:href="'{{ route('file_detail') }}/' + val._id" v-html="val.search"></a>
					</div>
				</td>
				<td class="view-tablet-only">
					<div v-if="val.type == 'incoming_mail'" v-html="val.subject"></div>
					<div v-if="val.type == 'outgoing_mail'" v-html="val.subject"></div>
					<div v-if="val.type == 'file'" v-html="val.desc"></div>
				</td>
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<span class="color-blue">Surat Masuk</span>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<span class="color-green">Surat Keluar</span>
					</div>
					<div v-if="val.type == 'file'">
						<span class="color-gray">Berkas</span>
					</div>
				</td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li v-if="val.type == 'incoming_mail'">
							<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id">Lihat Detail</a>
						</li>
						<li v-if="val.type == 'outgoing_mail'">
							<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + val._id">Lihat Detail</a>
						</li>

						<li v-if="val.type == 'incoming_mail'">
							<a v-bind:href="'{{ route('incoming_mail_move') }}/' + val._id">Sunting</a>
						</li>
						<li v-if="val.type == 'outgoing_mail'">
							<a v-bind:href="'{{ route('outgoing_mail_move') }}/' + val._id">Sunting</a>
						</li>

						<li><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
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
			Data tidak ditemukan
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
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('search_delete') }}" method="post">
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
	<!-- End Modal -->
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
			<div class="select no-border" style="height: calc(100vh - 160px)">
				<div class="item" v-if="detail.search">
					<div v-if="detail.type == 'incoming_mail'">
						<label>Asal Surat</label>
					</div>
					<div v-if="detail.type == 'outgoing_mail'">
						<label>Tujuan Surat</label>
					</div>
					<div v-if="detail.type == 'file'">
						<label>Judul Berkas</label>
					</div>
					<div class="value" v-html="detail.search"></div>
				</div>
				<div class="item" v-if="detail.reference_number">
					<label>Nomor Surat</label>
					<div class="value" v-html="detail.reference_number"></div>
				</div>
				<div class="item" v-if="detail.desc">
					<label>Deskripsi</label>
					<div v-html="detail.desc"></div>
				</div>
				<div class="item" v-if="detail.subject">
					<label>Perihal</label>
					<div class="value" v-html="detail.subject"></div>
				</div>
				<div class="item" v-if="detail.storage != ''">
					<label>Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storage"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.storagesub != ''">
					<label>Sub Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storagesub"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.folder">
					<label>Folder</label>
					<div class="value"><a :href="'{{ route('folder') }}/' + detail.folder" v-html="detail.folder"></a></div>
				</div>
				<div class="item" v-if="detail.share[0].user != ''">
					<label>Disposisi</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a :href="'{{ route('incoming_mail_disposition_history') }}/' + detail._id" v-html="disposisi.user[0].name"></a></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.date">
					<div v-if="detail.type == 'incoming_mail'">
						<label>Tanggal Masuk</label>
					</div>
					<div v-if="detail.type == 'outgoing_mail'">
						<label>Tanggal Keluar</label>
					</div>
					<div v-if="detail.type == 'file'">
						<label>Tanggal Unggah</label>
					</div>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<div v-if="detail.type == 'incoming_mail'">
					<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
				</div>
				<div v-if="detail.type == 'outgoing_mail'">
					<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
				</div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/folder.js') }}"></script>
	<script>
		<?php
			// Sort By
			$sortKey = 'created_at';
			if (@$_GET['sort'] == 'search') {
				$sortKey = 'search';
			} else if (@$_GET['sort'] == 'subject') {
				$sortKey = 'subject';
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
		getDataFolderDetail('{{ route("api_folder_detail", ["folder" => $folder]) }}?sort={{ $sortKey }}&asc={{ $asc }}&page={{ $page }}', 'folder');

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection