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
				<li>{{ $storage->name }}</li>
			</ul>
		</div>

		<table class="table table-hover" v-if="orderedStorage != ''">
			<tr>
				<th class="sort" @click="sortBy('name', $event)">Nama/Kode map, folder, guide atau ordner <i class="fa fa-angle-down i-sort"></i></th>
				<th class="view-tablet-only" colspan="2">Jumlah Arsip</th>
			</tr>
			<tr class="item" v-for="val in orderedStorage" @click="detailSidebar(val, $event)">
				<td><a href="#" v-html="val.name"></a></td>
				<td class="view-tablet-only">
					<span v-html="val.count + ' arsip'" v-if="val.count > 0"></span>
					<span v-html="'-'" v-else></span>
				</td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name">Sunting</a></li>
						<li v-if="val.count > 0"><a href="#" data-toggle="modal" data-target="#deleteNotModal" class="text-danger">Hapus</a></li>
						<li v-else><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
					</ul>
				</td>
			</tr>
		</table>
		
		<div class="text-center" v-else>
			<hr>
			<img src="{{ url('assets/app/img/icons') }}/no_file.png" alt="" width="300px">
			<br>
			<br>
			<br>
			Belum ada sub penyimpanan
		</div>
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
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('storage_sub_store') }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="id_storage" value="{{ $storage->_id }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="newLabelModal">Tambah Media Penyimpanan Baru</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" name="name" class="form-control" placeholder="Nama/Kode Penyimpanan Arsip">
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
				<form action="{{ route('storage_sub_update') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Media Penyimpanan</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						<div class="form-group">
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
				<form action="{{ route('storage_sub_delete') }}" method="POST">
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

	<div class="modal fade" id="deleteNotModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="deleteLabelModal">Tidak Dapat Menghapus</h4>
				</div>
				<div class="modal-body">
					Silahkan pindahkan arsip-arsip di dalamnya sebelum Anda menghapus penyimpanan ini!
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal">Mengerti</button>
				</div>
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
		<div class="select no-border">
			<div class="item" v-if="detail.name">
				<label>Nama/Kode Penyimpanan</label>
				<div class="value" v-html="detail.name"></div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/penyimpanan-arsip.js') }}"></script>
	<script>
		getDataStorageSub('{{ route("api_storage_sub", ["id" => $storage->_id]) }}', 'subStorage');

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