@extends('app.layouts.main')

@section('title', 'Surat Masuk')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="index.html">Surat Masuk</a></li>
			</ul>
		</div>

		<table class="table table-hover">
			<tr>
				<th class="sort">Asal Surat <i class="fa fa-angle-down"></i></th>
				<th>Perihal</th>
				<th class="view-tablet-only" width="150px">Disposisi</th>
				<th class="view-tablet-only">Tanggal Masuk</th>
				<th class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i></a>
					<ul class="dropdown-menu pull-right">
						<li class="label-dropdown">Mode Tampilan</li>
						<li><a href="detail.html">List <i class="fa fa-list-ul pull-right"></i></a></li>
						<li><a href="#">Grid <i class="fa fa-th-large pull-right"></i></a></li>
					</ul>
				</th>
			</tr>
			<tr class="item" v-for="val in json.incomingMail" v-on:click="detailSidebar(val, $event)">
				<td><a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id" v-html="val.from"></a></td>
				<td v-html="val.subject"></td>
				<td class="view-tablet-only">
					<ul class="list-unstyled disposisi">
						<li v-for="disposisi in val.share" class="img-disposisi">
							<b-tooltip v-bind:content="disposisi.name" placement="bottom">
								<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + disposisi.photo + ')' }"></div>
							</b-tooltip>
						</li>
					</ul>
				</td>
				<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="detail.html">Lihat Detail</a></li>
						<li><a href="#" data-toggle="modal" data-target="#disposisiModal">Disposisi</a></li>
						<li><a v-bind:href="'{{ route('incoming_mail_edit') }}/' + val._id">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>

	<aside class="ka-sidebar-detail">
		<form action="{{ route('incoming_mail_upload') }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="file" v-on:change="inputFileSubmit" id="inputFileSubmit" name="image" class="hide" accept=".jpg, .png, .jpeg">
			<label for="inputFileSubmit" class="btn btn-primary btn-block">Tambah</label>
		</form>


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
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="disposisiLabelModal">Disposisi</h4>
					</div>
					<div class="modal-body">
						<table class="table">
							<tr>
								<td class="search" colspan="4"><input type="text" class="form-control" placeholder="Cari" v-model="search"></td>
							</tr>
							<tr v-for="val in filteredUsers">
								<td class="text-center"><input type="checkbox"></td>
								<td><div class="img-profile" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + val.photo + ')' }"></div></td>
								<td>
									<span class="name" v-html="val.name"></span><br>
									<span class="position" v-html="val.position"></span>
								</td>
							</tr>
						</table>
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
				<form action="{{ route('incoming_mail_delete') }}" method="post">
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
				<div class="item" v-if="detail.from">
					<label>Asal Surat</label>
					<div class="value" v-html="detail.from"></div>
				</div>
				<div class="item" v-if="detail.reference_number">
					<label>Nomor Surat</label>
					<div class="value" v-html="detail.reference_number"></div>
				</div>
				<div class="item" v-if="detail.subject">
					<label>Perihal</label>
					<div class="value" v-html="detail.subject"></div>
				</div>
				<div class="item" v-if="detail.storage">
					<label>Penyimpanan Arsip</label>
					<div class="value" v-html="detail.storage"></div>
				</div>
				<div class="item" v-if="detail.share != ''">
					<label>Disposisi</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a href="" v-html="disposisi.name"></a></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.date">
					<label>Tanggal Masuk</label>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/surat-masuk.js') }}"></script>
	<script>
		getDataIncomingMail('{{ route("api_incoming_mail") }}', 'incomingMail');
	</script>
	<script>
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection