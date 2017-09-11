@extends('app.layouts.main')

@section('title', 'Anggota')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('member') }}">Anggota</a></li>
			</ul>
		</div>

		<table class="table table-hover">
			<tr>
				<th class="sort" @click="sortBy('name', $event)">Nama Lengkap <i class="fa fa-angle-down i-sort"></i></th>
				<th @click="sortBy('position', $event)">Jabatan</th>
				<th colspan="2"></th>
			</tr>
			<tr class="item va-mid" v-for="val in orderedUsers" @click="detailSidebar(val, $event)">
				<td>
					<a class="disposisi">
						<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users')}}/'+ val.photo +')' }"></div>&nbsp;&nbsp;&nbsp;
						<span v-html="val.name"></span>
					</a>
				</td>
				<td v-html="val.position"></td>
				<td><div class="badge badge-line" v-html="val.status"></div></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a v-bind:href="'{{ route('member_edit') }}/' + val._id">Sunting</a></li>
						<li v-if="val.status != 'admin'"><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>

	<aside class="ka-sidebar-detail">
		<a href="{{ route('member_create') }}" class="btn btn-primary btn-block">Tambah</a>

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
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('member_delete') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus anggota ini?
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
			<img src="assets/app/img/icons/select_file.svg" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div class="select no-border">
			<div class="item" v-if="detail.photo">
				<label>Foto Anggota</label>
				<div class="value">
					<img :src="'{{ asset('assets/app/img/users') }}/' + detail.photo" :alt="detail.photo">
				</div>
			</div>
			<div class="item" v-if="detail.name">
				<label>Nama Lengkap</label>
				<div class="value" v-html="detail.name"></div>
			</div>
			<div class="item" v-if="detail.position">
				<label>Jabatan</label>
				<div class="value" v-html="detail.position"></div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/pengguna.js') }}"></script>
	<script>
		getDataUsers('{{ route("api_member") }}', 'users');

		// Delete Modal
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection
