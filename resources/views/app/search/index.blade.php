@extends('app.layouts.main')

@section('title', 'Pencarian')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				@if (@$_GET['q'] != '')
					<li>Hasil pencarian dari "<b>{{ $_GET['q'] }}</b>"</li>
				@else
					<li>Hasil pencarian</li>
				@endif
				<li class="pull-right"><a href="#" data-toggle="modal" data-target="#filterModal"><i class="fa fa-sliders"></i> &nbsp;Filter</a></li>
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

		<table class="table table-hover">
			<tr>
				<th class="sort">Judul Arsip <i class="fa fa-angle-down"></i></th>
				<th class="view-tablet-only">Keterangan</th>
				<th width="150px">Jenis Arsip</th>
				<th class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i></a>
					<ul class="dropdown-menu pull-right">
						<li class="label-dropdown">Mode Tampilan</li>
						<li><a href="detail.html">List <i class="fa fa-list-ul pull-right"></i></a></li>
						<li><a href="#">Grid <i class="fa fa-th-large pull-right"></i></a></li>
					</ul>
				</th>
			</tr>
			<tr class="item" v-for="val in json.search" @click="detailSidebar(val, $event)">
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id" v-html="val.search"></a>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + val._id" v-html="val.search"></a>
					</div>
				</td>
				<td class="view-tablet-only" v-html="val.subject"></td>
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<span class="color-blue">Surat Masuk</span>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<span class="color-green">Surat Keluar</span>
					</div>
				</td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="detail.html">Lihat Detail</a></li>
						<li><a href="sunting.html">Sunting</a></li>
						<li><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
					</ul>
				</td>
			</tr>

			<tr v-if="!json.search">
				<td colspan="5" class="text-center">
					<br>
					<br>
					<img src="{{ url('assets/app/img/icons') }}/no_file.svg" alt="" width="400px">
					<br>
					<br>
					Belum ada data
				</td>
			</tr>

		</table>

		@if ($archieve->lastPage() > 1)
		<div class="text-center">
			<hr class="m-t-0">
			<ul class="pagination-custom">
				<li class="{{ ($archieve->currentPage() == 1) ? ' hide' : '' }}">
					<a href="{{ $archieve->url($archieve->currentPage()-1) }}">Sebelumnya</a>
				</li>
				@for ($i = 1; $i <= $archieve->lastPage(); $i++)
					<li class="{{ ($archieve->currentPage() == $i) ? ' active' : '' }}">
						<a href="{{ $archieve->url($i) }}">{{ $i }}</a>
					</li>
				@endfor
				<li class="{{ ($archieve->currentPage() == $archieve->lastPage()) ? ' hide' : '' }}">
					<a href="{{ $archieve->url($archieve->currentPage()+1) }}" >Selanjutnya</a>
				</li>
			</ul>
		</div>
		@endif
	</div>

	<aside class="ka-sidebar-detail">
		<form action="create.html">
			<input type="file" @change="inputFileSubmit" id="inputFileSubmit" name="image" class="hide" accept=".jpg, .png, .jpeg">
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
	<div class="modal fade modal-filter" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="shareLabelModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="shareLabelModal">Filter Pencarian</h4>
					</div>
					<div class="modal-body" style="border-top: 1px solid #ddd">
						<div class="form-group">
							<label for="">Full text (<i>Fitur OCR memungkinkan Anda mencari teks dalam gambar hasil scan</i>)</label>
							<input name="fulltext" class="form-control">
						</div>

						<div class="form-group">
							<label for="">Jenis Arsip</label>
							<select name="type" id="" class="form-control">
								<option value="">Semua Jenis Arsip</option>
								<option value="">Surat Masuk</option>
								<option value="">Surat Keluar</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">Rentang waktu diarsipkan</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="form-control" name="start" />
								<span class="input-group-addon" style="border-left: 0; border-right: 0">sampai</span>
								<input type="text" class="form-control" name="end" />
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Penyimpanan Arsip</label>
									<select name="storage" id="" class="form-control">
										<option value="">Semua Penyimpanan</option>
										<option value="">Filling Cabinet 1</option>
										<option value="">Filling Cabinet 2</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Sub Penyimpanan Arsip</label>
									<select name="substorage" id="" class="form-control">
										<option value="">Semua Sub Penyimpanan</option>
										<option value="">Ordner 1</option>
										<option value="">Ordner 2</option>
									</select>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Filter</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="">
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
		<div>
			<div class="select">
				<div class="item" v-if="detail.search">
					<label>Asal Surat</label>
					<div class="value" v-html="detail.search"></div>
				</div>
				<div class="item" v-if="detail.reference_number">
					<label>Nomor Surat</label>
					<div class="value" v-html="detail.reference_number"></div>
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
	<script src="{{ asset('assets/app/vue/pencarian.js') }}"></script>
	<script>
		getDataSearch('{{ route("api_search", ["q" => $_GET["q"]]) }}', 'search');

		// Date Picker
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		@if (@$_GET['q'] != '')
			$('input[name="q"]').val("{{ @$_GET["q"] }}");
		@endif
	</script>
@endsection