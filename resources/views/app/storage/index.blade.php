@extends('app.layouts.main')

@section('title', 'Penyimpanan Arsip')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif
	
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('storage') }}">Penyimpanan Arsip</a></li>
			</ul>
		</div>

		<table class="table table-hover">
			<tr>
				<th class="sort" @click="sortBy('name', $event)">Nama/Kode Penyimpanan Arsip <i class="fa fa-angle-down i-sort"></i></th>
				<th colspan="2" @click="sortBy('type', $event)">Jenis</th>
			</tr>
			<tr class="item" v-for="val in orderedStorage" @click="detailSidebar(val, $event)">
				<td><a v-bind:href="'{{ route('storage_sub') }}/' + val._id" v-html="val.name"></a></td>
				<td v-html="val.type.replace('_', ' ').replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})"></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-type="val.type">Sunting</a></li>
						<li><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
					</ul>
				</td>
			</tr>
		</table>
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
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newLabelModal">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form action="{{ route('storage_store') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="newLabelModal">Tambah Media Penyimpanan Baru</h4>
					</div>
					<div class="modal-body">
						<input type="radio" name="type" class="hide" id="filling-cabinet" value="filling_cabinet" checked>
						<input type="radio" name="type" class="hide" id="rotary-cabinet" value="rotary_cabinet">
						<input type="radio" name="type" class="hide" id="lemari-arsip" value="lemari_arsip">
						<input type="radio" name="type" class="hide" id="rak-arsip" value="rak_arsip">

						<div class="row select-cabinet">
							<div class="col-md-3 text-center">
								<label for="filling-cabinet" class="text-center active">
									<img src="{{ asset('assets/app/img/icons/filling_cabinet.svg') }}" width="80%">
									<p>Filling Cabinet</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="rotary-cabinet" class="text-center">
									<img src="{{ asset('assets/app/img/icons/rotary_cabinet.svg') }}" width="80%">
									<p>Rotary Cabinet</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="lemari-arsip" class="text-center">
									<img src="{{ asset('assets/app/img/icons/lemari_arsip.svg') }}" width="80%">
									<p>Lemari Arsip</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="rak-arsip" class="text-center">
									<img src="{{ asset('assets/app/img/icons/rak_arsip.svg') }}" width="80%">
									<p>Rak Arsip</p>
								</label>
							</div>
						</div>

						<div class="form-group">
							<br>
							<input type="text" name="name" class="form-control" placeholder="Nama/Kode Penyimpanan Arsip">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Tambah</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form action="{{ route('storage_update') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Media Penyimpanan</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">

						<input type="radio" name="type" class="hide" id="edit-filling-cabinet" value="filling_cabinet" checked>
						<input type="radio" name="type" class="hide" id="edit-rotary-cabinet" value="rotary_cabinet">
						<input type="radio" name="type" class="hide" id="edit-lemari-arsip" value="lemari_arsip">
						<input type="radio" name="type" class="hide" id="edit-rak-arsip" value="rak_arsip">

						<div class="row select-cabinet">
							<div class="col-md-3 text-center">
								<label for="edit-filling-cabinet" class="text-center active">
									<img src="{{ asset('assets/app/img/icons/filling_cabinet.svg') }}" width="80%">
									<p>Filling Cabinet</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="edit-rotary-cabinet" class="text-center">
									<img src="{{ asset('assets/app/img/icons/rotary_cabinet.svg') }}" width="80%">
									<p>Rotary Cabinet</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="edit-lemari-arsip" class="text-center">
									<img src="{{ asset('assets/app/img/icons/lemari_arsip.svg') }}" width="80%">
									<p>Lemari Arsip</p>
								</label>
							</div>

							<div class="col-md-3 text-center">
								<label for="edit-rak-arsip" class="text-center">
									<img src="{{ asset('assets/app/img/icons/rak_arsip.svg') }}" width="80%">
									<p>Rak Arsip</p>
								</label>
							</div>
						</div>

						<div class="form-group">
							<br>
							<input type="text" name="name" class="form-control" placeholder="Nama/Kode Penyimpanan Arsip">
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
				<form action="{{ route('storage_delete') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus data ini?
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
			<img src="{{ asset('assets/app/img/icons/select_file.svg') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div class="select no-border">
			<div class="item" v-if="detail.name">
				<label>Nama/Kode Penyimpanan Arsip</label>
				<div class="value" v-html="detail.name"></div>
			</div>
			<div class="item" v-if="detail.type">
				<div class="text-center">
					<img :src="'{{ asset('assets/app/img/icons') }}/' + detail.type + '.svg'">
					<p v-html="detail.type.replace('_', ' ').replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})"></p>
				</div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/penyimpanan-arsip.js') }}"></script>
	<script>
		getDataStorage('{{ route("api_storage") }}', 'storage');

		// Radio Button For Select Cabinet
		$('.select-cabinet label').click(function() {
			var id = $(this).attr('for');

			$('input[type="radio"]').removeAttr('checked');
			$('input#' + id).attr('checked', 'checked');

			$('label').removeClass('active');
			$('label[for="' + id + '"]').addClass('active');
		});

		// Edit Modal
		$('#editModal').on('show.bs.modal', function (e) {
			var key = ['id', 'name'];
			for (var i = 0; i < key.length; i++) {
				$(this).find('input[name="' + key[i] + '"]').val($(e.relatedTarget).data(key[i]));
			}

			// For Type
			var type = 'edit-' + $(e.relatedTarget).data('type').replace('_', '-');
			$(this).find('input#' + type).attr('checked', 'checked');
			$(this).find('label').removeClass('active');
			$(this).find('label[for="' + type + '"]').addClass('active');
		});

		// Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection