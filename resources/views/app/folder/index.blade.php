@extends('app.layouts.main')

@section('title', 'Folder')

@section('contents')

	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('trash') }}">Folder</a></li>
			</ul>
		</div>

		@if (Auth::user()->id_company == '')
			<div class="alert alert-warning">Anda belum mendaftarkan/bergabung perusahaan Anda, <a href="{{ route('setting') }}">klik disini!</a></div>
		@endif

		@if (Auth::user()->phone == '')
			<div class="alert alert-warning">Silahkan lengkapi profil Anda, <a href="{{ route('setting') }}?tab=account">klik disini!</a></div>
		@endif

		<table class="table table-hover" v-if="json.folder != ''">
			<tr>
				<th>Nama Folder</th>
				<th width="150px" class="view-tablet-only" colspan="2">Jumlah Arsip</th>
			</tr>
			<tr class="item" v-for="val in json.folder" @click="detailSidebar(val, $event)">
				<td>
					<img class="img" src="{{ url('assets/app/img/icons/folder.png') }}" alt="" height="30px">
					<a :href="'{{ route('folder_detail') }}/' + val.folder.replace(' ','_')" v-html="val.folder"></a>
				</td>
				<td class="view-tablet-only" v-html="val.count + ' arsip'"></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-folder="val.folder">Sunting</a></li>
						<li><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-folder="val.folder">Hapus Folder</a></li>
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
			<img src="{{ url('assets/app/img/icons') }}/no_file.png" alt="" width="300px">
			<br>
			<br>
			<br>
			Belum ada folder
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
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('folder_update') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Folder</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="old_folder">
						<div class="form-group">
							<input type="text" name="folder" class="form-control" placeholder="Nama Folder">
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
				<form action="{{ route('folder_delete') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="folder">
						Apakah Anda yakin ingin menghapus folder ini, semua isi folder ini juga akan ikut terhapus?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-danger">Ya, hapus</button>
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
			<img src="{{ asset('assets/app/img/icons/select_file.png') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select no-border select-full" style="height: calc(100vh - 160px)">
				<div class="item" v-if="detail.folder">
					<label>Nama Folder</label>
					<div class="value" v-html="detail.folder"></div>
				</div>
				<div class="item" v-if="detail.folder">
					<label>Jumlah Arsip</label>
					<div class="value" v-html="detail.count + ' arsip'"></div>
				</div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/folder.js') }}"></script>
	<script>
		getDataFolder('{{ route("api_folder") }}', 'folder');

		// Edit Modal
		$('#editModal').on('show.bs.modal', function (e) {
			var folder = $(e.relatedTarget).data('folder');
			$(this).find('input[name="old_folder"]').val(folder);
			$(this).find('input[name="folder"]').val(folder);
		});

		// Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var folder = $(e.relatedTarget).data('folder');
			$(this).find('input[name="folder"]').val(folder);
		});
	</script>
@endsection